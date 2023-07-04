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
            ->filterColumn('cashflow_type', function($query, $keyword) {
                switch (strtolower($keyword)){
                    case 'Pemasukan': case 'masuk': case 'pemasukan':
                        $match = WalletLog::CASHFLOW_TYPE_IN;
                        break;
                    case 'Pengeluaran': case 'keluar': case 'pengeluaran':
                        $match = WalletLog::CASHFLOW_TYPE_OUT;
                        break;
                    case 'Saldo': case 'Awal': case 'saldo': case 'awal': case 'saldo awal': case 'sal':
                        $match = WalletLog::CASHFLOW_TYPE_INIT;
                        break;
                    default:
                        $match = null;
                }
                $query->where('cashflow_type', $match);
            })
            ->toJson();
    }
}
