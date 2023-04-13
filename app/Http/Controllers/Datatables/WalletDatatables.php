<?php

namespace App\Http\Controllers\Datatables;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class WalletDatatables extends Controller
{
    //
    public function index()
    {
        $wallet = Wallet::latest('created_at');
        return DataTables::of($wallet)
            ->editColumn('last_balance', function ($row) {
                return $row->balance;
            })
            ->addColumn('action', function (Wallet $row) {
                $data = [
                    'edit_url'     => route('wallet.edit', ['wallet' => $row->id]),
                    'delete_url'   => route('wallet.destroy', ['wallet' => $row->id]),
                    'redirect_url' => route('wallet.index'),
                    'resource'     => 'wallet',
                ];
                return view('components.datatable-action', $data);
            })->toJson();
    }
}
