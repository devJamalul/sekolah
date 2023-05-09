<?php

namespace App\Actions\Wallet;

use App\Models\Wallet;
use App\Models\WalletLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletTransaction
{
    public static function increment(Wallet|int|string $wallet, int $nominal, string $note = null, bool $increment = true)
    {
        if (is_int($wallet)) {
            $dompet = Wallet::findOrFail($wallet);
        } else if (is_string($wallet)) {
            $dompet = Wallet::firstWhere('name', $wallet);
        } else if ($wallet instanceof Wallet) {
            $dompet = $wallet;
        } else {
            return;
        }

        if (!is_int($nominal)) {
            return;
        }

        DB::beginTransaction();

        try {
            $dompet->wallet_logs()->create([
                'cashflow_type' => $increment ? WalletLog::CASHFLOW_TYPE_IN : WalletLog::CASHFLOW_TYPE_OUT,
                'amount' => $nominal,
                'note' => $note
            ]);

            if ($increment) {
                $dompet->increment('last_balance', $nominal);
            } else {
                $dompet->decrement('last_balance', $nominal);
            }
            DB::commit();
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), [
                'action' => 'Store wallet saldo',
                'user' => auth()->user()->name ?? "Tinker",
                'data' => [
                    'wallet' => $dompet->name ?? "unknown wallet",
                    'nominal' => $nominal
                ]
            ]);
            DB::rollBack();
        }
        return $dompet->balance;
    }

    public static function decrement(Wallet|int|string $wallet, int $nominal, string $note = null)
    {
        return self::increment($wallet, $nominal, $note, false);
    }
}
