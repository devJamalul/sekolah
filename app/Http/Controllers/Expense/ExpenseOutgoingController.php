<?php

namespace App\Http\Controllers\Expense;

use App\Actions\Sempoa\PushToJurnalSempoa;
use App\Http\Controllers\Controller;
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
     * Display the specified resource.
     */
    public function show(Expense $expense_outgoing)
    {
        try {
            if ($expense_outgoing->status != Expense::STATUS_APPROVED)
                throw new \Exception('Transaksi tidak bisa dilakukan. Status harus "Disetujui".');

            $data = [
                'title' => "Realisasi Pengeluaran Biaya",
                'expense' => $expense_outgoing,
            ];
        } catch (\Throwable $th) {
            return redirect()->back()->withToastError('Ops! ' . $th->getMessage());
        }

        return view('pages.expense-outgoing.detail', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense_outgoing)
    {
        DB::beginTransaction();

        try {
            if ($expense_outgoing->status != Expense::STATUS_APPROVED)
                throw new \Exception('Transaksi tidak bisa dilakukan. Status harus "Disetujui".');

            $expense_outgoing->approval_by = Auth::user()->id;
            $expense_outgoing->status = Expense::STATUS_DONE;
            $expense_outgoing->expense_outgoing_date = now();

            // Upload Expense Photo
            if ($request->hasFile('file_photo')) {
                $uploadedFile = $request->file('file_photo');
                if ($expense_outgoing->file_photo) Storage::delete($expense_outgoing->getRawOriginal('file_photo')); // Delete old photo
                $expense_outgoing->file_photo = Storage::putFileAs('expense_photo', $uploadedFile, $uploadedFile->hashName());
            }
            $expense_outgoing->save();

            DB::commit();
            // kurangi saldo
            WalletTransaction::decrement($expense_outgoing->wallet, $expense_outgoing->price, 'Expense ' . $expense_outgoing->expense_number . ' - ' . $expense_outgoing->note);
            // sempoa
            PushToJurnalSempoa::handle($expense_outgoing);
            // Notification
            $expense_outgoing->requested_by->notify(new ExpenseOutgoingNotification($expense_outgoing));
        } catch (\Throwable $th) {
            DB::rollBack();
            return to_route('expense-outgoing.index')->withToastError('Ops! ' . $th->getMessage());
        }

        return to_route('expense-outgoing.index')->withToastSuccess('Berhasil mengubah Status!');
    }
}
