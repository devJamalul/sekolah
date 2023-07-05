<?php

namespace App\Http\Controllers\Invoice;

use App\Actions\Sempoa\PushToJurnalSempoa;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PublishInvoiceController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Invoice $invoice)
    {
        if ($invoice->school_id != session('school_id')) abort(404);

        DB::beginTransaction();
        try {
            // cek status dan kembalikan jika statusnya sudah PUBLISHED
            if ($invoice->is_posted == Invoice::POSTED_PUBLISHED or $invoice->is_posted == Invoice::POSTED_SENT)
                throw new \Exception('Invoice sudah pernah diterbitkan.');

            // cek status dan kembalikan jika statusnya bukan DRAFT
            if ($invoice->is_posted != Invoice::POSTED_DRAFT)
                throw new \Exception('Invoice tidak berhak untuk diterbitkan.');

            // cek harus memiliki invoice_details
            if (count($invoice->invoice_details) == 0)
                throw new \Exception('Invoice belum memiliki baris data.');

            // cek harus memiliki nilai lebih dari 0
            if ($invoice->total_amount <= 0)
                throw new \Exception('Invoice tidak boleh bernilai 0.');

            // ambil status kirim emailnya
            $is_sent = $request->get('sent') ?? false;

            $invoice->is_posted = Invoice::POSTED_PUBLISHED;
            if ($is_sent) $invoice->is_posted = Invoice::POSTED_SENT;
            $invoice->save();
            DB::commit();
            if ($is_sent) {
                // todo: kirim email di App\Http\Controllers\Invoice\PublishInvoiceController
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), [
                'action' => 'Publish invoice',
                'user' => auth()->user()->name,
                'invoice' => $invoice
            ]);
            DB::rollback();
            return to_route('invoices.index')->withToastError('Ups! ' . $th->getMessage());
        }
        return to_route('invoices.index')->withToastSuccess('Invoice berhasil diterbitkan!');
    }
}
