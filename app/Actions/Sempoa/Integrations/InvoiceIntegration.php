<?php

namespace App\Actions\Sempoa\Integrations;

use App\Models\Invoice;
use App\Models\SempoaConfiguration;

class InvoiceIntegration
{
    public static function handle(Invoice $invoice, SempoaConfiguration $config) : array {
        // instance
        $items = [];

        // kredit
        if (is_null($config->invoice_credit_account) and is_null($invoice->credit_account)) {
            throw new \Exception('Akun kredit Invoice belum terkonfigurasi');
        }
        $credit_account = $config->invoice_credit_account;
        if ($invoice->credit_account) {
            $credit_account = $invoice->credit_account;
        }

        array_push($items, [
            // credit
            'akun' => $credit_account,
            'debit' => 0,
            'kredit' => $invoice->total_amount
        ]);

        // debit
        $debit_account = $config->invoice_debit_account;
        // cek satu per satu invoice_detail
        foreach ($invoice->invoice_details as $detail) {
            if (!is_null($detail->wallet->sempoa_wallet->account)) {
                $debit_account = $detail->wallet->sempoa_wallet->account;
            }

            if (is_null($debit_account)) {
                throw new \Exception('Akun debit Invoice belum terkonfigurasi');
            }

            array_push($items, [
                'akun' => $debit_account,
                'debit' => $detail->price,
                'kredit' => 0
            ]);
        }

        $result['deskripsi'] = 'Invoice Sekolah #' . $invoice->getKey();
        $result['referensi'] = $invoice->invoice_number;
        $result['tanggal'] = $invoice->invoice_date;
        $result['transaksi'] = $items;
        return $result;
    }
}
