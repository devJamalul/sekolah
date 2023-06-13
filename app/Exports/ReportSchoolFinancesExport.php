<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\WalletLog;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
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
        $cacheName = str('school_finance_report_data' . $request->cashflow_type . '-' . $request->wallet_id . '-' . $request->reportrange)->slug();

        if (Cache::has($cacheName)) {
            $WalletLog = Cache::get($cacheName);
        } else {
            $WalletLog = WalletLog::with('wallet')->where('wallet_id', $request->wallet_id)
                ->orderBy('created_at', 'DESC')
                ->when($request->has('reportrange'), function ($q) use ($request) {
                    $reportDate = $this->parseDate($request->reportrange);
                    $q->whereBetween('created_at', [
                        $reportDate->transaction_report_start->startOfDay()->format('Y-m-d H:i:s'),
                        $reportDate->transaction_report_end->endOfDay()->format('Y-m-d H:i:s'),
                    ]);
                })
                ->when(in_array($request->cashflow_type, [WalletLog::CASHFLOW_TYPE_IN, WalletLog::CASHFLOW_TYPE_OUT, WalletLog::CASHFLOW_TYPE_INIT]), function ($q) use ($request) {
                    $q->where('cashflow_type', $request->cashflow_type);
                })->get();
            Cache::put($cacheName, $WalletLog, config('school.cache_time'));
        }
        return view('exports.report-school-finance', compact('WalletLog'));
    }

    public function parseDate($tanggal): object
    {
        $tgl = explode(" - ", $tanggal);

        return (object) [
            'transaction_report_start' => Carbon::parse($tgl[0]),
            'transaction_report_end' => Carbon::parse($tgl[1])
        ];
    }
}
