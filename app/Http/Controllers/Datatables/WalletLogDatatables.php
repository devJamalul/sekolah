<?php

namespace App\Http\Controllers\Datatables;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletLog;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class WalletLogDatatables extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Wallet $wallet)
    {
        $data = WalletLog::where('wallet_id', $wallet->getKey())->orderByDesc('created_at');
        return DataTables::of($data)
            ->editColumn('amount', function ($row) {
                return "Rp. " . number_format($row->amount, 0, ',', '.');
            })
            ->editColumn('cashflow_type', function ($row) {
                return match ($row->cashflow_type) {
                    WalletLog::CASHFLOW_TYPE_IN => 'Pemasukan',
                    WalletLog::CASHFLOW_TYPE_OUT => 'Pengeluaran',
                    WalletLog::CASHFLOW_TYPE_INIT => 'Saldo Awal'
                };
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d F Y H:i');
            })
            ->toJson();
    }
}
