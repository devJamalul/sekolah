<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;

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
        $wallet = Wallet::find($request->wallet_id);
        $queryParameter = $this->queryParameter($request);
        $title = $this->title;
        return view('pages.report.school-finances.report', compact('title', 'wallet', 'queryParameter'));
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
