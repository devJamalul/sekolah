<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Exports\ExpenseExport;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = "Laporan Pengeluaran Biaya";
        return view('pages.expense-report.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        self::parseDate($request->reportrange);
        $title = (session('expense_report_start')->format('d F Y') == session('expense_report_end')->format('d F Y'))
            ? session('expense_report_start')->format('d F Y')
            : session('expense_report_start')->format('d F Y') . " s/d " . session('expense_report_end')->format('d F Y');
        $data['title'] = "Laporan Pengeluaran Biaya : " . $title;

        return view('pages.expense-report.list', $data);
    }

    public function parseDate($tanggal): void
    {
        $tgl = explode(" - ", $tanggal);

        session(['expense_report_start' => Carbon::parse($tgl[0])]);
        session(['expense_report_end' => Carbon::parse($tgl[1])]);
    }

    public function exportExpenseReport(Request $request)
    {
        $query = null;
        $expense = Expense::with('expense_details')->where('school_id', session('school_id'))->get();

        switch ($request->action) {
            case 'excel':
                return Excel::download(new ExpenseExport($expense), "data_pengeluaran_biaya_". Carbon::parse(now())->format('d-m-Y') .".xlsx");
                break;
            case 'pdf':
                return PDF::loadView('exports.report-expense', compact('expense'))->setPaper('a4', 'landscape')->download("data_pengeluaran_biaya_". Carbon::parse(now())->format('d-m-Y') .".pdf");
                break;
        }
    }
}
