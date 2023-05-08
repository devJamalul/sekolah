<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceDetailRequest;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Illuminate\Support\Facades\DB;

class InvoiceDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Invoice $invoice)
    {
        if ($invoice->school_id != session('school_id')) abort(404);

        $data['title'] = "Invoice " . $invoice->invoice_number;
        $data['details'] = $invoice->invoice_details;
        $data['invoice'] = $invoice;

        // cek status dan bedakan view-nya jika statusnya bukan DRAFT
        if ($invoice->is_posted != Invoice::POSTED_DRAFT) {
            return view('pages.invoices.detail.show', $data);
        }

        return view('pages.invoices.detail.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InvoiceDetailRequest $request, Invoice $invoice)
    {
        if($invoice->school_id != session('school_id')) abort(404);

        DB::beginTransaction();
        try {
            $invoiceDetail = new InvoiceDetail();
            $invoiceDetail->invoice_id = $invoice->getKey();
            $invoiceDetail->item_name = $request->item_name;
            $invoiceDetail->price = formatAngka($request->price);
            $invoiceDetail->save();

            $invoiceDetail->invoice->total_amount = $invoiceDetail->invoice->invoice_details()->sum('price');
            $invoiceDetail->push();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withToastError('Ups! ' . $th->getMessage());
        }
        return redirect()->back()->withToastSuccess('Berhasil menambah baris invoice!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice, InvoiceDetail $invoiceDetail)
    {
        if ($invoice->school_id != session('school_id') or $invoiceDetail->invoice != $invoice) abort(404);

        $data['title'] = "Invoice " . $invoice->invoice_number;
        $data['detail'] = $invoiceDetail;
        $data['invoice'] = $invoice;
        return view('pages.invoices.detail.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InvoiceDetailRequest $request, Invoice $invoice, InvoiceDetail $invoiceDetail)
    {
        if ($invoice->school_id != session('school_id') or $invoiceDetail->invoice != $invoice) abort(404);

        DB::beginTransaction();
        try {
            $invoiceDetail->item_name = $request->item_name;
            $invoiceDetail->price = formatAngka($request->price);
            $invoiceDetail->save();

            $invoiceDetail->invoice->total_amount = $invoiceDetail->invoice->invoice_details()->sum('price');
            $invoiceDetail->push();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withToastError('Ups! ' . $th->getMessage());
        }
        return to_route('invoice-details.index', $invoice->getKey())->withToastSuccess('Berhasil mengubah baris invoice!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice, InvoiceDetail $invoiceDetail)
    {
        if ($invoice->school_id != session('school_id') or $invoiceDetail->invoice != $invoice) abort(404);

        DB::beginTransaction();
        try {
            $invoiceDetail->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withToastError('Ups! ' . $th->getMessage());
        }
        return redirect()->back()->withToastSuccess('Berhasil menghapus baris invoice!');
    }
}
