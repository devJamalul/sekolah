<?php

namespace App\Actions\Sempoa\Integrations;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\SempoaConfiguration;

class ExpenseIntegration
{
    public static function handle(Expense $expense, SempoaConfiguration $config) : array {
        // debit
        $debit_account = $config->expense_debit_account;
        if (!is_null($expense->debit_account)) {
            $debit_account = $expense->debit_account;
        }
        if (is_null($debit_account)) {
            throw new \Exception('Akun debit Invoice belum terkonfigurasi');
        }
        // credit
        $credit_account = $config->expense_credit_account;
        if ($expense->wallet->sempoa_wallet->account) {
            $credit_account = $expense->wallet->sempoa_wallet->account;
        }
        if (is_null($credit_account)) {
            throw new \Exception('Akun kredit Invoice belum terkonfigurasi');
        }

        // arrange
        $items = [
            [
                // debit
                'akun' => $debit_account,
                'debit' => $expense->price,
                'kredit' => 0
            ],
            [
                // credit
                'akun' => $credit_account,
                'debit' => 0,
                'kredit' => $expense->price
            ],
        ];

        $result['deskripsi'] = 'Pengeluaran Biaya Sekolah #' . $expense->getKey();
        $result['referensi'] = $expense->expense_number;
        $result['tanggal'] = $expense->expense_date;
        $result['transaksi'] = $items;

        return $result;
    }
}
