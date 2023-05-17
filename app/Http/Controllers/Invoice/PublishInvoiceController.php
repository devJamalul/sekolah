<?php

namespace App\Http\Controllers\Invoice;

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

        // cek status dan kembalikan jika statusnya sudah PUBLISHED
        if ($invoice->is_posted == Invoice::POSTED_PUBLISHED or $invoice->is_posted == Invoice::POSTED_SENT)
            return to_route('invoices.index')->withToastError('Ups! Invoice sudah pernah diterbitkan.');

        // cek status dan kembalikan jika statusnya bukan DRAFT
        if ($invoice->is_posted != Invoice::POSTED_DRAFT)
            return to_route('invoices.index')->withToastError('Ups! Invoice tidak berhak untuk diterbitkan.');

        // cek harus memiliki invoice_details
        if (count($invoice->invoice_details) == 0)
            return to_route('invoices.index')->withToastError('Ups! Invoice belum memiliki baris data.');

        // ambil status kirim emailnya
        $is_sent = $request->get('sent') ?? false;

        DB::beginTransaction();
        try {
            $invoice->is_posted = Invoice::POSTED_PUBLISHED;
            if ($is_sent) $invoice->is_posted = Invoice::POSTED_SENT;
            $invoice->save();
            DB::commit();
            if ($is_sent) {
                // to-do kirim email
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), [
                'action' => 'Publish invoice',
                'user' => auth()->user()->name,
                'invoice' => $invoice
            ]);
            DB::rollback();
            return to_route('invoices.index')->withToastError('Ups, terjadi kesalahan saat publish invoice!');
        }
        return to_route('invoices.index')->withToastSuccess('Invoice berhasil diterbitkan!');
    }
}
