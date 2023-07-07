<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TransactionReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = "Laporan Transaksi";
        return view('pages.transaction-report.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        self::parseDate($request->reportrange);
        $title = (session('transaction_report_start')->format('d F Y') == session('transaction_report_end')->format('d F Y'))
            ? session('transaction_report_start')->format('d F Y')
            : session('transaction_report_start')->format('d F Y') . " s/d " . session('transaction_report_end')->format('d F Y');
        $data['title'] = "Laporan Transaksi : " . $title;

        return view('pages.transaction-report.list', $data);
    }

    public function parseDate($tanggal): void
    {
        $tgl = explode(" - ", $tanggal);

        session(['transaction_report_start' => Carbon::parse($tgl[0])]);
        session(['transaction_report_end' => Carbon::parse($tgl[1])]);
    }
}
