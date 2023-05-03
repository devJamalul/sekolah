<?php

namespace App\Http\Controllers\Datatables;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laraindo\TanggalFormat;
use Yajra\DataTables\DataTables;

class InvoiceDatatables extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $invoice = Invoice::with('invoice_details')->latest('created_at');
        return DataTables::of($invoice)
            ->addColumn('action', function ($row) {
                $data = [
                    'edit_url'     => route('invoices.edit', ['invoice' => $row->id]),
                    'delete_url'   => route('invoices.destroy', ['invoice' => $row->id]),
                    'redirect_url' => route('invoices.index'),
                    'resource'     => 'invoices',
                    'custom_links' => [
                        [
                            'label' => 'Terbitkan',
                            'url' => route('invoices.publish', ['invoice' => $row->id]),
                        ],
                        // [
                        //     'label' => 'Terbitkan & Kirim',
                        //     'url' => route('invoices.publish', ['invoice' => $row->id, 'sent' => true]),
                        // ],
                        [
                            'label' => 'Bayar',
                            'url' => route('invoices.pay', ['invoice' => $row->id]),
                        ]
                    ]
                ];
                return view('components.datatable-action', $data);
            })
            ->editColumn('invoice_number', function ($invoice) {
                return "<a href='" . route('invoice-details.index', $invoice->getKey()) . "'>{$invoice->invoice_number}</a>";
            })
            ->editColumn('invoice_date', fn ($invoice) => TanggalFormat::DateIndo($invoice->invoice_date))
            ->editColumn('due_date', fn ($invoice) => TanggalFormat::DateIndo($invoice->due_date))
            ->editColumn('payment_status', function ($invoice) {
                $result = match ($invoice->payment_status) {
                    Invoice::STATUS_PAID => '<span class="badge badge-success">Lunas</span>',
                    Invoice::STATUS_PENDING => '<span class="badge badge-secondary">Belum Lunas</span>',
                    Invoice::STATUS_PARTIAL => '<span class="badge badge-primary">Partial</span>',
                };

                $result .= match ($invoice->due_date < now() && $invoice->is_posted != Invoice::POSTED_DRAFT && $invoice->payment_status != Invoice::STATUS_PAID) {
                    true => ' <span class="badge badge-danger">Overdue</span>',
                    false => '',
                };

                return $result;
            })
            ->editColumn('is_posted', function ($invoice) {
                return match ($invoice->is_posted) {
                    Invoice::POSTED_DRAFT => '<span class="badge badge-secondary">Draft</span',
                    Invoice::POSTED_PUBLISHED => '<span class="badge badge-success">Terbit</span',
                    Invoice::POSTED_SENT => '<span class="badge badge-success">Terbit & Terkirim</span',
                };
            })
            ->rawColumns(['invoice_number', 'payment_status', 'is_posted'])
            ->make(true);
    }
}
