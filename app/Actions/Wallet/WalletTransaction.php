<?php

namespace App\Actions\Wallet;

use App\Models\Wallet;
use App\Models\WalletLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletTransaction
{
    public static function increment(Wallet|int|string $wallet, int $nominal, string $note = null, string|bool $increment = true)
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
            $increment = match($increment) {
                true => WalletLog::CASHFLOW_TYPE_IN,
                false => WalletLog::CASHFLOW_TYPE_OUT,
                'init' => WalletLog::CASHFLOW_TYPE_INIT,
            };

            $dompet->wallet_logs()->create([
                'cashflow_type' => $increment,
                'amount' => $nominal,
                'note' => $note
            ]);

            if ($increment == WalletLog::CASHFLOW_TYPE_IN) {
                $dompet->increment('last_balance', $nominal);
            }
            if ($increment == WalletLog::CASHFLOW_TYPE_OUT) {
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

    public static function init(Wallet|int|string $wallet, int $nominal, string $note = null)
    {
        return self::increment($wallet, $nominal, $note, 'init');
    }
}
