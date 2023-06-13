<?php

namespace App\Http\Controllers\Invoice;

use App\Exports\InvoiceExport;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceReportController extends Controller
{
    protected $title = "Laporan Invoice";

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = $this->title;
        $data['statuses'] = [
            Invoice::STATUS_PAID,
            Invoice::STATUS_PARTIAL,
            Invoice::STATUS_PENDING,
            Invoice::VOID,
        ];

        return view('pages.invoices.report.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        self::parseDate($request->range);

        if (Cache::has('invoice_report_data')) {
            $invoices = Cache::get('invoice_report_data');
        } else {
            $invoices = Invoice::query()
                ->where('is_posted', '!=', Invoice::POSTED_DRAFT)
                ->whereBetween('invoice_date', [
                    session('invoice_report_start')->startOfDay()->format('Y-m-d H:i:s'),
                    session('invoice_report_end')->endOfDay()->format('Y-m-d H:i:s'),
                ])
                ->when($request->payment_status != "*", function ($query) use ($request) {
                    $query->where('payment_status', $request->payment_status);
                })
                ->orderBy('invoice_date')
                ->orderBy('payment_status')
                ->get();

            Cache::put('invoice_report_data', $invoices, config('school.cache_time'));
        }

        $filename = "invoice dengan status " . str(($request->payment_status == '*') ? 'Semua' : $request->payment_status)->title . " periode " . session('invoice_report_start')->format('d F Y') . " -  " . session('invoice_report_end')->format('d F Y');

        if (count($invoices) == 0) {
            return redirect()->back()->withToastError("Ups! Tidak ada data invoice dengan status " . str(($request->payment_status == '*') ? 'Semua' : $request->payment_status)->title . " pada periode " . session('invoice_report_start')->format('d F Y') . " sampai " . session('invoice_report_end')->format('d F Y'));
        }

        switch ($request->action) {
            case 'excel':
                return Excel::download(new InvoiceExport($invoices, $filename), "$filename.xlsx");
                break;
            case 'pdf':
                $invoices = $invoices->toArray();
                return Pdf::loadView('exports.report-invoice-pdf', compact('invoices', 'filename'))->download("$filename.pdf");
                break;
        }
    }

    public function parseDate($tanggal): void
    {
        $tgl = explode(" - ", $tanggal);

        session(['invoice_report_start' => Carbon::parse($tgl[0])]);
        session(['invoice_report_end' => Carbon::parse($tgl[1])]);
    }
}
