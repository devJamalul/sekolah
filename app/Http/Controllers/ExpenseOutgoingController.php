<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Actions\Wallet\WalletTransaction;
use App\Notifications\ExpenseApprovalNotification;
use App\Notifications\ExpenseOutgoingNotification;

class ExpenseOutgoingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => "Realisasi Pengeluaran Biaya"
        ];

        return view('pages.expense-outgoing.index', $data);
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
    public function show(Expense $expense_outgoing)
    {
        $data = [
            'title' => "Realisasi Pengeluaran Biaya",
            'expense' => $expense_outgoing,
        ];
        return view('pages.expense-outgoing.detail', $data);
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
    public function update(Request $request, Expense $expense_outgoing)
    {
        DB::beginTransaction();
        
        try {
            $expense_outgoing->approval_by = Auth::user()->id;
            $expense_outgoing->status = Expense::STATUS_DONE;
              
            // Upload Expense Photo 
              if ($request->hasFile('file_photo')) {
                $uploadedFile = $request->file('file_photo');
                // dd($uploadedFile);
                if ($expense_outgoing->file_photo) Storage::delete($expense_outgoing->getRawOriginal('file_photo')); // Delete old photo
                $expense_outgoing->file_photo = Storage::putFileAs('expense_photo', $uploadedFile, $uploadedFile->hashName());
                // dd($expense_outgoing->file_photo);
            } 
            $expense_outgoing->save();

            DB::commit();

            // pengurangan saldo jika diterima
            if ($request->action == 'approve') {
                foreach ($expense_outgoing->expense_details as $detail) {
                    WalletTransaction::decrement($detail->wallet_id, $detail->quantity * $detail->price, 'Pengeluaran ' . $expense_outgoing->expense_number . ' untuk ' . $detail->item_name);
                }
            }

            // Notification
            $expense_outgoing->requested_by->notify(new ExpenseOutgoingNotification($expense_outgoing));

            return redirect()->route('expense-outgoing.index')->withToastSuccess('Berhasil mengubah Status!');
        } catch (\Throwable $th) {
            dd($th);
            DB::rollBack();
            return redirect()->back()->withToastError('Ops, ada kesalahan saat mengubah Status!');
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
