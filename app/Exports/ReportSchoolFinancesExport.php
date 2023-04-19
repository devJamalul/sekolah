<?php

namespace App\Exports;

use App\Models\WalletLog;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReportSchoolFinancesExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */

    function __construct(protected $request)
    {
    }


    public function view(): View
    {
        $request = $this->request;
        $WalletLog = WalletLog::with('wallet')->where('wallet_id', $request->wallet_id)
            ->when($request->has('reportrange'), function ($q) use ($request) {
                $reportDate = $this->parseDate($request->reportrange);
                $q->whereBetween('created_at', [
                    $reportDate->transaction_report_start->startOfDay()->format('Y-m-d H:i:s'),
                    $reportDate->transaction_report_end->endOfDay()->format('Y-m-d H:i:s'),
                ]);
            })
            ->when(in_array($request->cashflow_type, [WalletLog::CASHFLOW_TYPE_IN, WalletLog::CASHFLOW_TYPE_OUT]), function ($q) use ($request) {
                $q->where('cashflow_type', $request->cashflow_type);
            })->get();
        return view('exports.report-school-finance', compact('WalletLog'));
    }
}
