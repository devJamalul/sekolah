<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Wallet;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportSchoolFinancesExport;

class ReportSchoolFinancesController extends Controller
{

    protected $title = "Laporan Keuangan Sekolah";

    public function index()
    {

        $title = $this->title;
        $wallet = Wallet::all();
        return view('pages.report.school-finances.index', compact('title', 'wallet'));
    }

    public function report(Request $request)
    {
        $wallet = Wallet::findOrFail($request->wallet_id);
        $queryParameter = $this->queryParameter($request);
        $title = $this->title;
        return view('pages.report.school-finances.report', compact('title', 'wallet', 'queryParameter'));
    }

    public function export(Request $request)
    {
        $wallet = Wallet::where('id', $request->wallet_id)->firstOrFail();
        $schoolFinance = new ReportSchoolFinancesExport($request);
        $exportName = Str::slug('laporan-keuangan-sekolah');
        $exportName .= '-';
        $exportName .= Carbon::parse(now())->format('m-d-Y-hs');

        return Excel::download($schoolFinance, $exportName . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    private function queryParameter($request): array
    {
        if (is_null($request->reportrange) != true) {
            $filter['reportrange'] = $request->reportrange;
        }

        $filter['wallet_id'] = $request->wallet_id;
        $filter['cashflow_type'] = $request->cashflow_type;

        return $filter;
    }
}
