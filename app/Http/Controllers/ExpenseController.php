<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Expense;
use Illuminate\Http\Request;
use App\Models\ExpenseDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ExpenseRequest;

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
        $title = "Tambah Pengeluaran Biaya";
        $expenseNumber = Expense::whereYear('created_at', date('Y'))->withTrashed()->count();
        $users = User::where('school_id', session('school_id'))->whereHas('roles', function($q){
            $q->whereIn('name',['admin sekolah','admin yayasan','tata usaha','bendahara','kepala sekolah']);
        })->get();
        $wallets = Wallet::where('school_id', session('school_id'))->get();
        return view('pages.expense.create', compact('title', 'users', 'expenseNumber', 'wallets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseRequest $request)
    {
        DB::beginTransaction();

        try {

            $expense                    = new Expense();
            $expense->school_id         = session('school_id');
            $expense->expense_number    = $request->expense_number;
            $expense->expense_date      = $request->expense_date;
            $expense->status            = Expense::STATUS_PENDING;
            $expense->note              = $request->note;
            $expense->request_by        = Auth::id();
            $expense->save();
            
            $wallet         = Wallet::find($request->wallet_id);
            // $danaBOS        = Wallet::danaBos()->first();

            $totalExpensePending    = ExpenseDetail::whereHas('expense', function ($q) {
                $q->where('status', Expense::STATUS_PENDING);
            })
                ->where('wallet_id', $request->wallet_id)
                ->sum(DB::raw('price * quantity'));

            // $totalExpensePendingDanaBos    = ExpenseDetail::whereHas('expense', function ($q) {
            //     $q->where('status', Expense::STATUS_PENDING);
            // })
            //     ->where('wallet_id', $danaBOS->id)
            //     ->sum(DB::raw('price * quantity'));

            $walletBalance  = $wallet->balance - $totalExpensePending;
            // $walletBos      = $danaBOS->balance - $totalExpensePendingDanaBos;

            $expenseDetail              = new ExpenseDetail();
            $expenseDetail->expense_id  = $expense->getKey();

            if ((formatAngka($request->quantity) * formatAngka($request->price)) <= $walletBalance) {
                $expenseDetail->wallet_id   = $request->wallet_id;
            } 
            // else if ((formatAngka($request->quantity) * formatAngka($request->price)) <= $walletBos) {
            //     $expenseDetail->wallet_id   = $danaBOS->id;
            // } 
            else {
                DB::rollBack();
                return redirect()->route('expense.create')->withToastError('Eror! Saldo dompet ' . $wallet->name . ' tidak mencukupi untuk melakukan pengeluaran ini!');
            }

            $expenseDetail->item_name   = $request->item_name;
            $expenseDetail->quantity    = formatAngka($request->quantity);
            $expenseDetail->price       = formatAngka($request->price);
            $expenseDetail->save();



            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('expense.create')->withToastError('Eror Simpan Pengeluaran Biaya!');
        }

        return redirect()->route('expense.edit', $expense->id)->withToastSuccess('Berhasil Simpan Pengeluaran Biaya!');
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
        $title = 'Ubah Pengeluaran Biaya';
        $users = User::where('school_id', session('school_id'))->whereHas('roles', function($q){
            $q->whereIn('name',['admin sekolah','admin yayasan','tata usaha','bendahara','kepala sekolah']);
        })->get();
        $wallets = Wallet::where('school_id', session('school_id'))->get();
        return view('pages.expense.edit', compact('expense', 'title', 'users', 'wallets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpenseRequest $request, Expense $expense)
    {
        
        // cek status dan kembalikan jika statusnya bukan DRAFT
        if ($expense->approval_by != Null)
            return to_route('expense.index')->withToastError('Ups! Invoice tidak berhak untuk diubah.');


        DB::beginTransaction();

        try {

            $expense->school_id         = session('school_id');
            $expense->expense_date      = $request->expense_date;
            $expense->note              = $request->note;
            $expense->request_by        = Auth::id();
            $expense->save();


            $arrayMax = $request->array_max;
            foreach(range(0, $arrayMax) as $key => $item){
                $wallet         = Wallet::find($request->wallet_id);
                // $danaBOS        = Wallet::danaBos()->first();
    
                $totalExpensePending    = ExpenseDetail::whereHas('expense', function ($q) {
                    $q->where('status', Expense::STATUS_PENDING);
                })
                    ->where('wallet_id', $request->wallet_id)
                    ->where('id', '<>', $request->expense_detail_id[$key])
                    ->sum(DB::raw('price * quantity'));
                    
            $walletBalance  = $wallet->balance - $totalExpensePending;

            $expenseDetail              = ExpenseDetail::find($request->expense_detail_id[$key]);
            $expenseDetail->expense_id  = $expense->getKey();

            if ((formatAngka($request->array_quantity[$key]) * formatAngka($request->array_price[$key])) <= $walletBalance) {
                $expenseDetail->wallet_id   = $request->wallet_id;
            } 
            // else if ((formatAngka($request->quantity) * formatAngka($request->price)) <= $walletBos) {
            //     $expenseDetail->wallet_id   = $danaBOS->id;
            // } 
            else {
                return redirect()->route('expense.edit', $expense->getKey())->withToastError('Eror! Saldo dompet ' . $wallet->name . ' tidak mencukupi untuk melakukan pengeluaran ini!');
            }
                $expenseDetail = ExpenseDetail::updateOrCreate(
                    [
                        'id' => $request->expense_detail_id[$key]
                    ],
                    [
                        'wallet_id' => $request->array_wallet_id[$key],
                        'item_name' => $request->array_item_name[$key],
                        'quantity' => formatAngka($request->array_quantity[$key]),
                        'price' => formatAngka($request->array_price[$key])
                    ]
                );
                $expenseDetail->push();
            }

            DB::commit();

        } catch (\Throwable $th) {
            return redirect()->route('expense.edit', $expense->getKey())->withToastError('Eror Simpan Pengeluaran Biaya!');
        }

        return redirect()->route('expense.edit', $expense->getKey())->withToastSuccess('Berhasil Simpan Pengeluaran Biaya!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {

        try {
            if($expense->expense_details){
                $expenseDetails = ExpenseDetail::where('expense_id', $expense->id)->get();
                foreach ($expenseDetails as $key => $expenseDetail) {
                    $expenseDetail->delete();
                }
            }
            
            $expense->delete();


            DB::commit();

            return response()->json([
                'msg' => 'Berhasil Hapus Pengeluaran Biaya!'
            ], 200);

        } catch (\Throwable $th) {
            DB::rollback();

            return response()->json([
                'msg' => 'Eror Hapus Pengeluaran Biaya'
            ]);
        }
    }

    public function ShowDetail(Expense $expense)
    {
        $title = "Detail Pengeluaran Biaya";
        $wallets = Wallet::where('school_id', session('school_id'))->get();
        $expenseDetails = $expense->expense_details()->orderBy('wallet_id')->get();
        return view('pages.expense.show', compact('title', 'wallets', 'expenseDetails', 'expense'));
    }
}
