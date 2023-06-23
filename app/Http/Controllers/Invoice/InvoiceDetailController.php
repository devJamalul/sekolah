<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceDetailRequest;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
    public function store(Request $request, Invoice $invoice)
    {
        if ($invoice->school_id != session('school_id')) abort(404);

        if ($request->has('price')) {
            $request->merge([
                'price' => formatAngka($request->price)
            ]);
        }


        DB::beginTransaction();
        try {
            Validator::make($request->all(), [
                'item_name' => [
                    'required',
                    Rule::unique('invoice_details')->where(function ($q) use ($invoice, $request) {
                        $q->where('invoice_id', $invoice->id);
                        $q->where('item_name', $request->item_name);
                        $q->whereNull('deleted_at');
                    })
                ],
                'price' => 'required|min:0',
            ], [], [
                'item_name' => 'nama barang',
                'price' => 'harga'
            ])->validate();

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
            return to_route('invoices.edit', $invoice->getKey())->withInput()->withToastError('Ups! ' . $th->getMessage());
        }
        return to_route('invoices.edit', $invoice->getKey())->withToastSuccess('Berhasil menambah baris invoice!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice, InvoiceDetail $invoiceDetail)
    {
        if ($invoice->school_id != session('school_id') or $invoiceDetail->invoice != $invoice) abort(404);

        DB::beginTransaction();
        try {
            $invoiceDetail->forceDelete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->withToastError('Ups! ' . $th->getMessage());
        }

        return to_route('invoices.edit', $invoice->getKey())->withToastSuccess('Berhasil menghapus baris invoice!');
    }
}
