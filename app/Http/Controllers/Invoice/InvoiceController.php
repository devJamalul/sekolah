<?php

namespace App\Http\Controllers\Invoice;

use App\Actions\Invoice\CreateNewInvoiceNumber;
use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceRequest;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InvoiceController extends Controller
{
    protected $title = "Invoice";

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = $this->title;
        return view('pages.invoices.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = "Tambah " . $this->title;
        return view('pages.invoices.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, CreateNewInvoiceNumber $createNewInvoiceNumber)
    {
        if ($request->has('price')) {
            $request->merge([
                'price' => formatAngka($request->price)
            ]);
        }


        DB::beginTransaction();
        try {
            Validator::make(
                $request->all(),
                [
                    'invoice_number' => [
                        'nullable',
                        Rule::unique('invoices')->where(function ($q) use ($request) {
                            $q->where('invoice_number', $request->invoice_number);
                            $q->where('school_id', session('school_id'));
                            $q->whereNull('deleted_at');
                        })
                    ],
                    'note' => 'required|string',
                    'invoice_date' => 'required|date',
                    'due_date' => 'required|date|after:invoice_date',
                    'item_name' => 'required|string',
                    'price' => 'required|string',
                ],
                [],
                [
                    'due_date' => 'tanggal jatuh tempo',
                    'invoice_date' => 'tanggal invoice',
                    'note' => 'deskripsi',
                    'item_name' => 'nama barang',
                    'price' => 'harga'
                ]
            )->validate();
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

            return to_route('invoices.create')->withInput()->withToastError('Ups! ' . $th->getMessage());
        }

        return to_route('invoices.edit', $invoice->getKey())->withToastSuccess('Berhasil menambah invoice!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
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
    public function update(Request $request, Invoice $invoice)
    {
        if ($request->has('array_price')) {
            foreach ($request->array_price as $key => $price) {
                $request->merge([
                    'array_price.' . $key => formatAngka($price)
                ]);
            }
        }

        DB::beginTransaction();
        try {
            Validator::make(
                $request->all(),
                [
                    'invoice_number'      => [
                        'required',
                        Rule::unique('invoices')->where(function ($q) use ($request, $invoice) {
                            $q->where('invoice_number', $request->invoice_number);
                            $q->where('school_id', session('school_id'));
                            $q->whereNull('deleted_at');
                        })->ignore($invoice->id, 'id')
                    ],
                    'note' => 'required|string',
                    'invoice_date' => 'required|date',
                    'due_date' => 'required|date|after:invoice_date',
                    'invoice_detail_id' => 'required|array',
                    'array_item_name' => 'required|array',
                    'array_item_name.*' => 'required|string',
                    'array_price' => 'required|array',
                    'array_price.*' => 'required|string',
                ],
                [],
                [
                    'due_date' => 'tanggal jatuh tempo',
                    'invoice_date' => 'tanggal invoice',
                    'note' => 'deskripsi',
                    'array_item_name' => 'nama barang',
                    'array_price' => 'harga'
                ]
            )->validate();

            if ($invoice->school_id != session('school_id')) abort(404);

            // cek status dan kembalikan jika statusnya sudah PUBLISHED
            if ($invoice->is_original == false)
                throw new \Exception('Invoice tidak berhak untuk diubah');

            // cek status dan kembalikan jika statusnya bukan DRAFT
            if ($invoice->is_posted != Invoice::POSTED_DRAFT)
                throw new \Exception('Invoice tidak berhak untuk diubah');

            $invoice->invoice_number = $request->invoice_number;
            $invoice->note = $request->note;
            $invoice->invoice_date = $request->invoice_date;
            $invoice->due_date = $request->due_date;
            $invoice->save();

            // update invoice_details
            foreach ($request->invoice_detail_id as $key => $invoice_detail_id) {
                $invoiceDetail = InvoiceDetail::updateOrCreate(
                    [
                        'id' => $invoice_detail_id
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
            return to_route('invoices.edit', $invoice->getKey())->withInput()->withToastError('Ups! ' . $th->getMessage());
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
