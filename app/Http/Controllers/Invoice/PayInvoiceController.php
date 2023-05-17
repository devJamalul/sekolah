<?php

namespace App\Http\Controllers\Invoice;

use App\Actions\Wallet\WalletTransaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\PayInvoiceRequest;
use App\Models\Invoice;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Invoice $invoice)
    {
        if ($invoice->school_id != session('school_id')) abort(404);

        // cek status dan kembalikan jika statusnya bukan PUBLISHED
        if ($invoice->is_posted != Invoice::POSTED_PUBLISHED)
            return to_route('invoices.index')->withToastError('Ups! Pembayaran tidak bisa dilakukan. Invoice harus diterbitkan dahulu.');

        // cek pembayaran dan kembalikan jika pembayarannya sudah LUNAS
        if ($invoice->payment_status == Invoice::STATUS_PAID)
            return to_route('invoices.index')->withToastError('Ups! Invoice sudah dinyatakan lunas');

        // cek pembayaran dan kembalikan jika invoice tidak dibuat dari halaman invoice
        if ($invoice->is_original == false)
            return to_route('invoices.index')->withToastError('Ups! Pembayaran tidak bisa dilakukan. Invoice berasal dari transaksi lain.');

        // cek harus memiliki invoice_details
        if (count($invoice->invoice_details) == 0)
            return to_route('invoices.index')->withToastError('Ups! Invoice belum memiliki baris data.');

        $data['title'] = "Invoice " . $invoice->invoice_number;
        $data['details'] = $invoice->invoice_details()->orderBy('id')->get();
        $data['invoice'] = $invoice;
        $data['wallets'] = Wallet::all();
        return view('pages.invoices.payment.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PayInvoiceRequest $request, Invoice $invoice)
    {
        if ($invoice->school_id != session('school_id')) abort(404);

        // cek status dan kembalikan jika statusnya bukan PUBLISHED
        if ($invoice->is_posted != Invoice::POSTED_PUBLISHED)
            return to_route('invoices.index')->withToastError('Ups! Pembayaran tidak bisa dilakukan. Invoice harus diterbitkan dahulu.');

        // cek pembayaran dan kembalikan jika pembayarannya sudah LUNAS
        if ($invoice->payment_status == Invoice::STATUS_PAID)
            return to_route('invoices.index')->withToastError('Ups! Invoice sudah dinyatakan lunas');

        // cek pembayaran dan kembalikan jika invoice tidak dibuat dari halaman invoice
        if ($invoice->is_original == false)
            return to_route('invoices.index')->withToastError('Ups! Pembayaran tidak bisa dilakukan. Invoice berasal dari transaksi lain.');

        // cek harus memiliki invoice_details
        if (count($invoice->invoice_details) == 0)
            return to_route('invoices.index')->withToastError('Ups! Invoice belum memiliki baris data.');

        DB::beginTransaction();
        try {
            $inv_details = $invoice->invoice_details()->orderBy('id')->get();

            foreach ($inv_details as $key => $inv_detail) {
                $inv_detail->wallet_id = $request->wallet_id[$key];
                $inv_detail->save();
            }

            $invoice->payment_status = Invoice::STATUS_PAID;
            $invoice->save();
            DB::commit();
            $invoice->refresh();
            foreach ($invoice->invoice_details as $key => $inv_detail) {
                $note = "Pembayaran Invoice #" . $invoice->invoice_number . ", " . $inv_detail->item_name;
                WalletTransaction::increment($inv_detail->wallet->id, $inv_detail->price, $note);
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), [
                'action' => 'pembayaran invoice',
                'user' => auth()->user()->name,
                'invoice' => $invoice
            ]);
            DB::rollback();
            return to_route('invoices.index')->withToastError('Ups, terjadi kesalahan saat pembayaran invoice!');
        }
        return to_route('invoices.index')->withToastSuccess('Invoice berhasil dibayarkan!');
    }
}
