<?php

namespace App\Actions\Sempoa\Integrations;

use App\Models\Invoice;
use App\Models\SempoaConfiguration;
use App\Models\StudentTuition;

class StudentTuitionIntegration
{
    public static function handle(StudentTuition $invoice, SempoaConfiguration $config): array
    {
        // instance
        $items = [];

        // kredit
        if (is_null($config->tuition_credit_account)) {
            throw new \Exception('Akun kredit Pembayaran Sekolah belum terkonfigurasi');
        }
        $credit_account = $config->tuition_credit_account;

        array_push($items, [
            // credit
            'akun' => $credit_account,
            'debit' => 0,
            'kredit' => $invoice->grand_total
        ]);

        // debit
        $debit_account = $config->tuition_debit_account;
        // cek satu per satu tuition_detail
        foreach ($invoice->student_tuition_payment_histories as $detail) {
            if (!is_null($detail->payment_type->wallet?->sempoa_wallet?->account)) {
                $debit_account = $detail->payment_type->wallet?->sempoa_wallet?->account;
            }

            if (is_null($debit_account)) {
                throw new \Exception('Akun debit Pembayaran Sekolah belum terkonfigurasi');
            }

            array_push($items, [
                'akun' => $debit_account,
                'debit' => $invoice->grand_total,
                'kredit' => 0
            ]);
        }

        $result['deskripsi'] = 'Pembayaran Sekolah #' . $invoice->getKey();
        $result['referensi'] = $invoice->bill_number;
        $result['tanggal'] = $invoice->student_tuition_payment_history->created_at;
        $result['transaksi'] = $items;
        return $result;
    }
}
