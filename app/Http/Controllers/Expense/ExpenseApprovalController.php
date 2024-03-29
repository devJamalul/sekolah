<?php

namespace App\Http\Controllers\Expense;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Actions\Wallet\WalletTransaction;
use App\Notifications\ExpenseApprovalNotification;

class ExpenseApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => "Persetujuan Pengeluaran Biaya"
        ];

        return view('pages.expense-approval.index', $data);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense_approval)
    {
        $data = [
            'title' => "Persetujuan Pengeluaran Biaya",
            'expense' => $expense_approval,
        ];
        return view('pages.expense-approval.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense_approval)
    {
        DB::beginTransaction();

        try {
            switch ($request->action) {
                case 'approve':
                    $expense_approval->approval_by = Auth::user()->id;
                    $expense_approval->approval_at = now();
                    $expense_approval->status = Expense::STATUS_APPROVED;
                    break;
                case 'reject':
                    if ($request->reject_reason == null) {
                        throw new \Exception('Alasan penolakan wajib diisi.');
                    }
                    $expense_approval->status = Expense::STATUS_REJECTED;
                    $expense_approval->reject_reason  = $request->reject_reason;
                    $expense_approval->rejected_by =  Auth::user()->id;
                    $expense_approval->rejected_at = now();
                    break;
            }
            $expense_approval->save();

            DB::commit();


            // Notification
            $expense_approval->requested_by->notify(new ExpenseApprovalNotification($expense_approval));

            return redirect()->route('expense-approval.index')->withToastSuccess('Berhasil mengubah Status!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withToastError('Ups! ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
