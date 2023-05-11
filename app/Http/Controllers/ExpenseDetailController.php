<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Expense;
use Illuminate\Http\Request;
use App\Models\ExpenseDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ExpenseDetailRequest;

class ExpenseDetailController extends Controller
{
    /**e
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseDetailRequest $request)
    {
        DB::beginTransaction();

        try {

            $wallet         = Wallet::find($request->wallet_id);
            $danaBOS        = Wallet::danaBos()->first();

            $totalExpensePending    = ExpenseDetail::whereHas('expense', function($q) {
                $q->where('status', Expense::STATUS_PENDING);
            })->sum(DB::raw('price * quantity'));
            
            $walletBalance  = $wallet - $totalExpensePending;
            $walletBos      = $danaBOS - $totalExpensePending;

            
            $expenseDetail              = new ExpenseDetail();
            $expenseDetail->expense_id  = $request->expense_id;
            
            if(formatAngka($request->quantity) * formatAngka($request->price) < $walletBalance){

                $expenseDetail->wallet_id   = $request->wallet_id;
            
            }
            
            else if(formatAngka($request->quantity) * formatAngka($request->price) < $walletBos){

                $expenseDetail->wallet_id   = $danaBOS->id;

            }
            
            else{

                $walletName = Wallet::find($expenseDetail->wallet_id);

                return redirect()->route('expense.show', $request->expense_id)->withToastError('Eror! Saldo dompet '. $walletName->name .' tidak mencukupi untuk melakukan pengeluaran ini!');
           
            }

                $expenseDetail->item_name   = $request->item_name;
                $expenseDetail->quantity    = formatAngka($request->quantity);
                $expenseDetail->price       = formatAngka($request->price);
                $expenseDetail->save();

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('expense.show', $request->expense_id)->withToastError('Eror Simpan Detail Pengeluaran!');
        }

        return redirect()->route('expense.show', $request->expense_id)->withToastSuccess('Berhasil Simpan Detail Pengeluaran!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExpenseDetail $expenseDetail)
    {
        $title = 'Ubah Detail Pengeluaran Biaya';
        $wallets = Wallet::all();
        return view('pages.expense.detail.edit', compact('title', 'expenseDetail', 'wallets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpenseDetailRequest $request, ExpenseDetail $expenseDetail)
    {
        DB::beginTransaction();

        try {

            $expenseDetail->wallet_id       = $request->wallet_id;
            $expenseDetail->item_name       = $request->item_name;
            $expenseDetail->quantity        = formatAngka($request->quantity);
            $expenseDetail->price           = formatAngka($request->price);
            $expenseDetail->save();

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('expense.show', $expenseDetail->expense_id)->withToastError('Eror Simpan Detail Pengeluaran Biaya!');
        }

        return redirect()->route('expense.show', $expenseDetail->expense_id)->withToastSuccess('Berhasil Simpan Detail Pengeluaran Biaya!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseDetail $expenseDetail)
    {
        DB::beginTransaction();

        try {

            $expenseDetail->delete();
            DB::commit();

            return response()->json([
                'msg' => 'Berhasil Hapus Detail Pengeluaran Biaya!'
            ], 200);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'msg' => 'Eror Hapus Detail Pengeluaran Biaya'
            ]);
        }
    }
}
