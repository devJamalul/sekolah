<?php

namespace App\Http\Controllers\Expense;

use App\Actions\Sempoa\GetAccount;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\SempoaConfiguration;
use App\Notifications\ExpenseNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Pengeluaran Biaya";
        return view('pages.expense.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = "Tambah Pengeluaran Biaya";
        $data['expenseNumber'] = Expense::whereYear('created_at', date('Y'))->withTrashed()->count();
        $data['users'] = User::where('school_id', session('school_id'))->whereHas('roles', function ($q) {
            $q->whereIn('name', ['admin sekolah', 'admin yayasan', 'tata usaha', 'bendahara', 'kepala sekolah']);
        })->get();
        $data['wallets'] = Wallet::where('school_id', session('school_id'))->get();
        $data['config'] = SempoaConfiguration::first();
        $data['accounts'] = [];
        if ($data['config']) $data['accounts'] = GetAccount::run();
        return view('pages.expense.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->has('price')) {
            $request->merge([
                'price' => formatAngka($request->price)
            ]);
        }

        DB::beginTransaction();

        try {
            Validator::make(
                $request->all(),
                [
                    'expense_number'      => [
                        'required',
                        Rule::unique('expenses')->where(function ($q) use ($request) {
                            $q->where('expense_number', $request->expense_number);
                            $q->where('school_id',  session('school_id'));
                            $q->whereNull('deleted_at');
                        })
                    ],
                    'expense_date' => 'required|date',
                    'note' => 'required|string',
                    'price' => 'required|string',
                    'debit_account' => 'sometimes|required|string',
                    'wallet_id' => 'required|exists:wallets,id',
                ],
                [
                    'note.required' => 'Deskripsi harus diisi',
                    'wallet_id.required' => 'Sumber biaya harus diisi',
                    'price.required' => 'Nominal harus diisi',
                    'expense_date.required' => 'Tanggal pengeluaran biaya harus diisi',
                    'debit_account.required' => 'Akun pengeluaran biaya harus diisi',
                    'expense_number.required' => 'Nomor pengeluaran biaya harus diisi',
                    'expense_number.unique' => 'Nomor pengeluaran biaya sudah terpakai',
                ]
            )->validate();

            if ($request->missing('debit_account')) {
                $request->merge([
                    'debit_account' => null
                ]);
            }

            // instance
            $expense = new Expense();

            // cek saldo
            $wallet = Wallet::find($request->wallet_id);
            $totalExpensePending = Expense::query()
                ->whereIn('status', [Expense::STATUS_DRAFT, Expense::STATUS_PENDING, Expense::STATUS_APPROVED])
                ->where('wallet_id', $request->wallet_id)
                ->sum('price');
            $walletBalance = $wallet->balance - $totalExpensePending;
            if (formatAngka($request->price) <= $walletBalance) {
                $expense->wallet_id   = $request->wallet_id;
            } else {
                throw new \Exception('Saldo dompet ' . $wallet->name . ' tidak mencukupi untuk melakukan pengeluaran ini!');
            }

            $expense->school_id         = session('school_id');
            $expense->expense_number    = $request->expense_number;
            $expense->expense_date      = $request->expense_date;
            $expense->status            = Expense::STATUS_DRAFT;
            $expense->note              = $request->note;
            $expense->debit_account     = $request->debit_account;
            $expense->request_by        = Auth::id();
            $expense->price             = formatAngka($request->price);
            $expense->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('expense.create')->withInput()->withToastError('Ups! ' . $th->getMessage());
        }

        return redirect()->route('expense.index')->withToastSuccess('Berhasil Simpan Pengeluaran Biaya!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        $title = "Tambah Detail Pengeluaran Biaya";
        $wallets = Wallet::where('school_id', session('school_id'))->get();
        $expenseDetails = $expense->expense_details()->orderBy('wallet_id')->get();
        return view('pages.expense.detail.create', compact('title', 'wallets', 'expenseDetails', 'expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        try {
            if ($expense->status != Expense::STATUS_DRAFT) {
                throw new \Exception('Pengeluaran Biaya sudah tidak bisa diubah.');
            }

            $data['expense'] = $expense;
            $data['title'] = 'Ubah Pengeluaran Biaya';
            $data['users'] = User::where('school_id', session('school_id'))->whereHas('roles', function ($q) {
                $q->whereIn('name', ['admin sekolah', 'admin yayasan', 'tata usaha', 'bendahara', 'kepala sekolah']);
            })->get();
            $data['wallets'] = Wallet::where('school_id', session('school_id'))->get();
            $data['config'] = SempoaConfiguration::first();
            $data['accounts'] = [];
            if ($data['config']) $data['accounts'] = GetAccount::run();

            return view('pages.expense.edit', $data);
        } catch (\Throwable $th) {
            return redirect()->route('expense.index')->withInput()->withToastError('Ups! ' . $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        if ($request->has('price')) {
            $request->merge([
                'price' => formatAngka($request->price)
            ]);
        }
        try {
            DB::beginTransaction();
            if ($expense->status != Expense::STATUS_DRAFT) {
                throw new \Exception('Pengeluaran Biaya sudah tidak bisa diubah.');
            }

            Validator::make(
                $request->all(),
                [
                    'expense_number'      => [
                        'required',
                        Rule::unique('expenses')->where(function ($q) use ($request) {
                            $q->where('expense_number', $request->expense_number);
                            $q->where('school_id',  session('school_id'));
                            $q->whereNull('deleted_at');
                        })->ignore($expense->id, 'id')
                    ],
                    'expense_date' => 'required|date',
                    'note' => 'required|string',
                    'price' => 'required|string',
                    'debit_account' => 'sometimes|required|string',
                    'wallet_id' => 'required|exists:wallets,id',
                ],
                [
                    'note.required' => 'Deskripsi harus diisi',
                    'wallet_id.required' => 'Sumber biaya harus diisi',
                    'price.required' => 'Nominal harus diisi',
                    'expense_date.required' => 'Tanggal pengeluaran biaya harus diisi',
                    'debit_account.required' => 'Akun pengeluaran biaya harus diisi',
                    'expense_number.required' => 'Nomor pengeluaran biaya harus diisi',
                    'expense_number.unique' => 'Nomor pengeluaran biaya sudah terpakai',
                ]
            )->validate();

            if ($request->missing('debit_account')) {
                $request->merge([
                    'debit_account' => null
                ]);
            }

            // cek saldo
            $wallet = Wallet::find($request->wallet_id);
            $totalExpensePending = Expense::query()
                ->whereIn('status', [Expense::STATUS_DRAFT, Expense::STATUS_PENDING, Expense::STATUS_APPROVED])
                ->where('wallet_id', $request->wallet_id)
                ->where('id', '<>', $expense->id)
                ->sum('price');
            $walletBalance = $wallet->balance - $totalExpensePending;
            if (formatAngka($request->price) <= $walletBalance) {
                $expense->wallet_id   = $request->wallet_id;
            } else {
                throw new \Exception('Saldo dompet ' . $wallet->name . ' tidak mencukupi untuk melakukan pengeluaran ini!');
            }

            $expense->expense_number    = $request->expense_number;
            $expense->expense_date      = $request->expense_date;
            $expense->debit_account     = $request->debit_account;
            $expense->note              = $request->note;
            $expense->price             = $request->price;
            $expense->request_by        = Auth::id();
            $expense->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('expense.edit', $expense->getKey())->withInput()->withToastError('Ups! ' . $th->getMessage());
        }

        return redirect()->route('expense.edit', $expense->getKey())->withToastSuccess('Berhasil Simpan Pengeluaran Biaya!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        try {
            if ($expense->status != Expense::STATUS_DRAFT) {
                throw new \Exception('Pengeluaran Biaya sudah tidak bisa dihapus');
            }
            $expense->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'msg' => 'Ups! ' . $th->getMessage()
            ]);
        }

        return response()->json([
            'msg' => 'Berhasil Hapus Pengeluaran Biaya!'
        ], 200);
    }

    public function ShowDetail(Expense $expense)
    {
        try {
            if ($expense->status == Expense::STATUS_DRAFT) {
                throw new \Exception('Anda tidak memiliki akses untuk melakukan ini');
            }

            $data['expense'] = $expense;
            $data['title'] = "Detail Pengeluaran Biaya";
            $data['wallets'] = Wallet::where('school_id', session('school_id'))->get();
            $data['extensionType'] = ['img', 'png', 'jpg', 'gif', 'jpeg'];
            $data['fileExtension'] = pathinfo($expense->file_photo, PATHINFO_EXTENSION);

            if ($expense->status == Expense::STATUS_APPROVED || $expense->status == Expense::STATUS_DONE) {
                $data['confirmation'] =  $expense->approved_by->name;
            } elseif ($expense->status == Expense::STATUS_REJECTED) {
                $data['confirmation'] = $expense->reject_by->name;
            } else {
                $data['confirmation'] = '-';
            }
            return view('pages.expense.show', $data);
        } catch (\Throwable $th) {
            return redirect()->route('expense.index')->withToastError('Ups! ' . $th->getMessage());
        }
    }

    public function ExpensePublish(Expense $expense)
    {
        DB::beginTransaction();
        try {
            if ($expense->status != Expense::STATUS_DRAFT) {
                throw new \Exception('Anda tidak memiliki akses untuk melakukan ini');
            }

            $config = SempoaConfiguration::first();
            if ($config and is_null($expense->debit_account)) {
                throw new \Exception('Akun pengeluaran biaya harus diisi');
            }

            $expense->status = Expense::STATUS_PENDING;
            $expense->save();
            DB::commit();

            $users = User::where('school_id', session('school_id'))->get();
            foreach ($users as $user) {
                if ($user->hasAnyRole([User::ROLE_ADMIN_SEKOLAH, User::ROLE_KEPALA_SEKOLAH])) {
                    $user->notify(new ExpenseNotification($expense));
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('expense.index')->withToastError('Ups! ' . $th->getMessage());
        }

        return redirect()->route('expense.index')->withToastSuccess('Berhasil Publish Pengeluaran Biaya!');
    }
}
