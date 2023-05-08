<?php

namespace App\Actions\Invoice;

use App\Actions\Wallet\WalletTransaction;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\StudentTuition;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AddToInvoice
{
    public function handle(StudentTuition $studentTuition, $nominal = null): void
    {
        $status_pembayaran = match ($studentTuition->status) {
            StudentTuition::STATUS_PAID => Invoice::STATUS_PAID,
            StudentTuition::STATUS_PENDING => Invoice::STATUS_PENDING,
            StudentTuition::STATUS_PARTIAL => Invoice::STATUS_PARTIAL,
        };

        DB::beginTransaction();

        try {
            $invoice = Invoice::updateOrCreate(
                [
                    'school_id' => session('school_id'),
                    'invoice_number' => $studentTuition->bill_number,
                ],
                [
                    'invoice_date' => $studentTuition->created_at,
                    'due_date' => $studentTuition->created_at->addMonth(),
                    'note' => $studentTuition->note . " milik " . $studentTuition->student->name,
                    'payment_status' => $status_pembayaran,
                    'is_posted' => Invoice::POSTED_PUBLISHED,
                    'total_amount' => $studentTuition->grand_total,
                ]
            );

            $inv_detail = InvoiceDetail::updateOrCreate(
                [
                    'invoice_id' => $invoice->getKey()
                ],
                [
                    'item_name' => $studentTuition->note,
                    'price' => $studentTuition->grand_total,
                    'wallet_id' => $studentTuition->payment_type->wallet->getKey()
                ]
            );

            DB::commit();

            $invoice->refresh();
            $inv_detail->refresh();
            WalletTransaction::increment($inv_detail->wallet_id, $nominal, $invoice->note);
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), [
                'action' => 'Store invoice via Transaction (SPP)',
                'user' => auth()->user()->name,
                'data' => $studentTuition
            ]);
            DB::rollBack();
        }
    }
}
