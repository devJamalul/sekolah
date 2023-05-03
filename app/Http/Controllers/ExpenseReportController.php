<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
}
