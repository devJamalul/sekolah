<?php

namespace App\Http\Controllers\Wallet;

use App\Actions\Wallet\WalletTransaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\WalletTopUpRequest;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TopUpWalletController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Wallet $wallet)
    {
        $data['title'] = 'Top Up ' . $wallet->name;
        $data['wallet'] = $wallet;

        return view('pages.wallet.topup', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WalletTopUpRequest $request, Wallet $wallet)
    {
        DB::beginTransaction();
        try {
            WalletTransaction::increment(
                wallet: $wallet,
                nominal: formatAngka($request->nominal),
                note: "Top Up oleh " . auth()->user()->name ?? "Sistem"
            );
            DB::commit();
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), [
                'action' => 'Top Up Wallet : ' . $wallet->name,
                'user' => auth()->user()->name ?? "Sistem",
                'wallet' => $wallet
            ]);
            DB::rollback();
            return redirect()->back()->withToastError('Ups, terjadi kesalahan saat top up ' . $wallet->name . '!');
        }

        return to_route('wallet.index')->withToastSuccess('Top up ' . $wallet->name . ' berhasil!');
    }
}
