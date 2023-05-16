<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VoidInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Invoice $invoice)
    {
        if ($invoice->school_id != session('school_id')) abort(404);

        // todo
        return to_route('invoices.index')->withToastError('Ups, mohon maaf! Fitur void masih dalam tahap pengembangan.');

        // cek status dan kembalikan jika statusnya sudah VOID
        if ($invoice->is_posted == Invoice::VOID)
            return to_route('invoices.index')->withToastError('Ups! Invoice tidak bisa di-void-kan kembali.');

        $data['title'] = "Konfirmasi Void | Invoice " . $invoice->invoice_number;
        $data['details'] = $invoice->invoice_details()->orderBy('id')->get();
        $data['invoice'] = $invoice;
        return view('pages.invoices.void.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Invoice $invoice)
    {
        if ($invoice->school_id != session('school_id')) abort(404);

        // todo
        return to_route('invoices.index')->withToastError('Ups, mohon maaf! Fitur void masih dalam tahap pengembangan.');

        // cek status dan kembalikan jika statusnya sudah VOID
        if ($invoice->is_posted == Invoice::VOID)
            return to_route('invoices.index')->withToastError('Ups! Invoice tidak bisa di-void-kan kembali.');

        DB::beginTransaction();
        try {
            $invoice->payment_status = Invoice::VOID;
            $invoice->is_posted = Invoice::VOID;
            $invoice->save();
            DB::commit();
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), [
                'action' => 'Void invoice',
                'user' => auth()->user()->name,
                'invoice' => $invoice
            ]);
            DB::rollback();
            return to_route('invoices.index')->withToastError('Ups, terjadi kesalahan saat void invoice!');
        }
        return to_route('invoices.index')->withToastSuccess('Invoice berhasil di-void-kan!');
    }
}
