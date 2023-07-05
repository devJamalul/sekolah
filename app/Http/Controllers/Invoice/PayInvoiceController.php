<?php

namespace App\Http\Controllers\Invoice;

use App\Actions\Sempoa\PushToJurnalSempoa;
use App\Actions\Wallet\WalletTransaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\PayInvoiceRequest;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
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

        DB::beginTransaction();
        try {
            if ($invoice->school_id != session('school_id')) abort(404);

            // cek status dan kembalikan jika statusnya bukan PUBLISHED
            if ($invoice->is_posted != Invoice::POSTED_PUBLISHED)
                throw new \Exception('Pembayaran tidak bisa dilakukan. Invoice harus diterbitkan dahulu.');

            // cek pembayaran dan kembalikan jika pembayarannya sudah LUNAS
            if ($invoice->payment_status == Invoice::STATUS_PAID)
                throw new \Exception('Invoice sudah dinyatakan lunas');

            // cek pembayaran dan kembalikan jika invoice tidak dibuat dari halaman invoice
            if ($invoice->is_original == false)
                throw new \Exception('Pembayaran tidak bisa dilakukan. Invoice berasal dari transaksi lain.');

            // cek harus memiliki invoice_details
            if (count($invoice->invoice_details) == 0)
                throw new \Exception('Invoice belum memiliki baris data.');

            // arrange
            foreach ($request->invoice_detail_id as $key => $inv_detail) {
                $detail = InvoiceDetail::find($inv_detail);
                $detail->wallet_id = $request->wallet_id[$key];
                $detail->save();
            }

            $invoice->payment_status = Invoice::STATUS_PAID;
            $invoice->save();
            DB::commit();

            foreach ($request->invoice_detail_id as $key => $inv_detail) {
                $detail = InvoiceDetail::find($inv_detail);
                $note = "Pembayaran Invoice #" . $invoice->invoice_number . ", " . $detail->item_name;
                WalletTransaction::increment($detail->wallet_id, $detail->price, $note);
            }

            $invoice->refresh();
            PushToJurnalSempoa::handle($invoice);
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), [
                'action' => 'Pembayaran invoice',
                'user' => auth()->user()->name,
                'invoice' => $invoice
            ]);
            DB::rollback();
            return to_route('invoices.index')->withToastError('Ups! ' . $th->getMessage());
        }
        return to_route('invoices.index')->withToastSuccess('Invoice berhasil dibayarkan!');
    }
}
