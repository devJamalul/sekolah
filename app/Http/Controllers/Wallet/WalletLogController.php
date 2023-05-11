<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Models\Wallet;

class WalletLogController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Wallet $wallet)
    {
        $data['title'] = "Log Dompet: " . $wallet->name;
        $data['wallet'] = $wallet;
        return view('pages.wallet.logs', $data);
    }
}
