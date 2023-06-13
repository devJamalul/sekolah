<?php

namespace App\Http\Controllers\Datatables;

use Carbon\Carbon;
use Laraindo\RupiahFormat;
use Laraindo\TanggalFormat;
use App\Models\WalletLog;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class ReportSchoolFinancesDatatables extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $WalletLog = WalletLog::where('wallet_id', $request->wallet_id)
            ->orderBy('created_at', 'DESC')
            ->when($request->has('reportrange'), function ($q) use ($request) {
                $reportDate = $this->parseDate($request->reportrange);
                $q->whereBetween('created_at', [
                    $reportDate->transaction_report_start->startOfDay()->format('Y-m-d H:i:s'),
                    $reportDate->transaction_report_end->endOfDay()->format('Y-m-d H:i:s'),
                ]);
            })
            ->when(in_array($request->cashflow_type, [WalletLog::CASHFLOW_TYPE_IN, WalletLog::CASHFLOW_TYPE_OUT]), function ($q) use ($request) {
                $q->where('cashflow_type', $request->cashflow_type);
            });

        return DataTables::of($WalletLog)
            ->editColumn('amount', function ($row) {
                return RupiahFormat::currency($row->amount);
            })
            ->addColumn('cashflow_type', function ($row) {
                return match ($row->cashflow_type) {
                    WalletLog::CASHFLOW_TYPE_IN => '<span class="badge badge-success">Masuk</span>',
                    WalletLog::CASHFLOW_TYPE_OUT => '<span class="badge badge-danger">Keluar</span>',
                    WalletLog::CASHFLOW_TYPE_INIT => '<span class="badge badge-primary">Saldo Awal</span>',
                };
            })
            ->editColumn('created_at', fn ($row) => TanggalFormat::DateIndo($row->created_at, 'Y M d H:i'))
            ->rawColumns(['cashflow_type'])
            ->toJson();
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
