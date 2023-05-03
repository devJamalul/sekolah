<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\Models\Invoice;
use Illuminate\Http\Request;
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
    public function store(InvoiceRequest $request)
    {
        DB::beginTransaction();
        try {
            $invoice = new Invoice();
            $invoice->school_id = session('school_id');
            $invoice->invoice_number = $request->invoice_number;
            $invoice->note = $request->note;
            $invoice->invoice_date = $request->invoice_date;
            $invoice->due_date = $request->due_date;
            $invoice->save();
            DB::commit();
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), [
                'action' => 'Store invoice',
                'user' => auth()->user()->name,
                'data' => $request->all()
            ]);
            DB::rollBack();
            return redirect()->back()->withToastError('Ups! ' . $th->getMessage());
        }
        return redirect()->route('invoice-details.index', $invoice->getKey())->withToastSuccess('Berhasil menambah invoice!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        if ($invoice->school_id != session('school_id')) abort(404);

        $data['title'] = "Ubah " . $this->title;
        $data['invoice'] = $invoice;
        return view('pages.invoices.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        if ($invoice->school_id != session('school_id')) abort(404);

        DB::beginTransaction();
        try {
            $invoice->invoice_number = $request->invoice_number;
            $invoice->note = $request->note;
            $invoice->invoice_date = $request->invoice_date;
            $invoice->due_date = $request->due_date;
            $invoice->save();
            DB::commit();
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), [
                'action' => 'Update invoice',
                'user' => auth()->user()->name,
                'invoice' => $invoice,
                'data' => $request->all()
            ]);
            DB::rollBack();
            return redirect()->back()->withToastError('Ups! ' . $th->getMessage());
        }
        return redirect()->route('invoices.index')->withToastSuccess('Berhasil mengubah invoice!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        if ($invoice->school_id != session('school_id')) abort(404);

        DB::beginTransaction();
        try {
            $invoice->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withToastError('Ups! ' . $th->getMessage());
        }
        return to_route('invoices.index')->withToastSuccess('Berhasil menghapus invoice!');
    }
}
