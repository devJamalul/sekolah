<?php

namespace App\Http\Controllers\Invoice;

use App\Actions\Invoice\CreateNewInvoiceNumber;
use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceRequest;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    protected $title = "Invoice";

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        #used
        $data['title'] = $this->title;
        return view('pages.invoices.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        #used
        $data['title'] = "Tambah " . $this->title;
        return view('pages.invoices.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InvoiceRequest $request, CreateNewInvoiceNumber $createNewInvoiceNumber)
    {
        #used
        DB::beginTransaction();
        try {
            $invoice = new Invoice();
            $invoice->school_id = session('school_id');
            $invoice->invoice_number = $request->invoice_number ?? $createNewInvoiceNumber->generate();
            $invoice->note = $request->note;
            $invoice->invoice_date = $request->invoice_date;
            $invoice->due_date = $request->due_date;
            $invoice->save();

            $invoiceDetail = new InvoiceDetail();
            $invoiceDetail->invoice_id = $invoice->getKey();
            $invoiceDetail->item_name = $request->item_name;
            $invoiceDetail->price = formatAngka($request->price);
            $invoiceDetail->save();

            $invoiceDetail->invoice->total_amount = $invoiceDetail->invoice->invoice_details()->sum('price');
            $invoiceDetail->push();
            DB::commit();
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), [
                'action' => 'Store invoice',
                'user' => auth()->user()->name,
                'data' => $request->all()
            ]);
            DB::rollBack();
            return to_route('invoices.create')->withToastError('Ups! ' . $th->getMessage());
        }
        return to_route('invoices.edit', $invoice->getKey())->withToastSuccess('Berhasil menambah invoice!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        #used
        if ($invoice->school_id != session('school_id')) abort(404);

        // cek status dan kembalikan jika statusnya bukan DRAFT
        if ($invoice->is_posted != Invoice::POSTED_DRAFT)
            return to_route('invoice-details.index', $invoice->getKey());

        // cek status dan kembalikan jika statusnya sudah PUBLISHED
        if ($invoice->is_original == false)
            return to_route('invoices.index')->withToastError('Ups! Invoice tidak berhak diubah.');

        $data['title'] = "Ubah " . $this->title;
        $data['invoice'] = $invoice;
        return view('pages.invoices.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InvoiceRequest $request, Invoice $invoice)
    {
        dd($request->all());
        #used
        if ($invoice->school_id != session('school_id')) abort(404);

        // cek status dan kembalikan jika statusnya sudah PUBLISHED
        if ($invoice->is_original == false)
            return to_route('invoices.index')->withToastError('Ups! Invoice tidak berhak diubah.');

        // cek status dan kembalikan jika statusnya bukan DRAFT
        if ($invoice->is_posted != Invoice::POSTED_DRAFT)
            return to_route('invoices.index')->withToastError('Ups! Invoice tidak berhak untuk diubah.');

        DB::beginTransaction();
        try {
            $invoice->invoice_number = $request->invoice_number;
            $invoice->note = $request->note;
            $invoice->invoice_date = $request->invoice_date;
            $invoice->due_date = $request->due_date;
            $invoice->save();

            // update invoice_details
            $array_max = $request->array_max;
            foreach (range(0, $array_max) as $key => $item) {
                info($request->array_price[$key]);
                $invoiceDetail = InvoiceDetail::updateOrCreate(
                    [
                        'id' => $request->invoice_detail_id[$key]
                    ],
                    [
                        'item_name' => $request->array_item_name[$key],
                        'price' => formatAngka($request->array_price[$key]),
                    ]
                );
                $invoiceDetail->invoice->total_amount = $invoiceDetail->invoice->invoice_details()->sum('price');
                $invoiceDetail->push();
            }

            DB::commit();
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), [
                'action' => 'Update invoice',
                'user' => auth()->user()->name,
                'invoice' => $invoice,
                'data' => $request->all()
            ]);
            DB::rollBack();
            return to_route('invoices.edit', $invoice->getKey())->withToastError('Ups! ' . $th->getMessage());
        }
        return redirect()->route('invoices.edit', $invoice->getKey())->withToastSuccess('Berhasil mengubah invoice!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        if ($invoice->school_id != session('school_id')) abort(404);

        // cek status dan kembalikan jika statusnya bukan DRAFT
        if ($invoice->is_posted != Invoice::POSTED_DRAFT)
            return response()->json([
                'msg' => 'Ups! Invoice tidak berhak untuk dihapus.'
            ], 500);

        DB::beginTransaction();
        try {
            $invoice->invoice_details()->delete();
            $invoice->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'msg' => 'Ups! ' . $th->getMessage()
            ], 500);
        }
        return response()->json([
            'msg' => 'Berhasil menghapus invoice!'
        ], 200);
    }
}
