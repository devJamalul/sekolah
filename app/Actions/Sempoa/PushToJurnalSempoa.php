<?php

namespace App\Actions\Sempoa;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\SempoaConfiguration;
use App\Models\StudentTuition;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PushToJurnalSempoa
{
    public static function handle(Invoice|Expense|StudentTuition $data)
    {
        try {
            //code...
            $data->sempoas()->create();
            $trx = $data->sempoas()->first();
            $config = SempoaConfiguration::first();
            $data->sempoa_processed = true;
            $data->save();

            if ($data instanceof Invoice) {
                $items = [
                    [
                        // debit
                        'akun' => $config->invoice_debit_account,
                        'debit' => $data->total_amount,
                        'kredit' => 0
                    ],
                    [
                        // credit
                        'akun' => $config->invoice_credit_account,
                        'debit' => 0,
                        'kredit' => $data->total_amount
                    ],
                ];

                $deskripsi = 'Invoice Sekolah #' . $data->getKey();
                $referensi = $data->invoice_number;
                $tanggal = $data->invoice_date;
                $transaksi = $items;
            }

            if ($data instanceof Expense) {
                $items = [
                    [
                        // debit
                        'akun' => $config->expense_debit_account,
                        'debit' => $data->price,
                        'kredit' => 0
                    ],
                    [
                        // credit
                        'akun' => $config->expense_credit_account,
                        'debit' => 0,
                        'kredit' => $data->price
                    ],
                ];

                $deskripsi = 'Pengeluaran Biaya Sekolah #' . $data->getKey();
                $referensi = $data->expense_number;
                $tanggal = $data->expense_date;
                $transaksi = $items;
            }

            $response = Http::withToken($config->token)
                ->post(config('sempoa.base_url') . 'jurnal', compact('deskripsi', 'referensi', 'tanggal', 'transaksi'));

            if (!$response->ok()) {
                throw new \Exception($response->body());
            }

            $trx->sempoa_id = $response['data']['id'];
            $trx->sempoa_type = 'App\Models\Transaction';
            $trx->save();


        } catch (\Throwable $th) {
            Log::error($th->getMessage(), [
                'action' => 'Push to Jurnal Sempoa',
                'user' => auth()->user()->name,
                'data' => $data
            ]);

            if ($data instanceof Invoice) {
                $data->sempoa_processed = false;
                $data->save();
            }
        }
    }
}
