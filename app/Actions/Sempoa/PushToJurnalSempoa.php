<?php

namespace App\Actions\Sempoa;

use App\Actions\Sempoa\Integrations\ExpenseIntegration;
use App\Actions\Sempoa\Integrations\InvoiceIntegration;
use App\Actions\Sempoa\Integrations\StudentTuitionIntegration;
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
            $config = SempoaConfiguration::first();
            $data->sempoa_processed = true;
            $data->save();

            if (!$config) {
                throw new \Exception('Belum terhubung dengan Sempoa');
            }

            if ($data instanceof StudentTuition) {
                $journal_items = StudentTuitionIntegration::handle(
                    invoice: $data,
                    config: $config
                );
            }

            if ($data instanceof Invoice) {
                $journal_items = InvoiceIntegration::handle(
                    invoice: $data,
                    config: $config
                );
            }

            if ($data instanceof Expense) {
                $journal_items = ExpenseIntegration::handle(
                    expense: $data,
                    config: $config
                );
            }

            $response = Http::withToken($config->token)
                ->post(
                    config('sempoa.base_url') . 'jurnal',
                    $journal_items
                );

            if (!$response->ok()) {
                throw new \Exception($response->body());
            }


            $data->sempoas()->create();
            $trx = $data->sempoas()->first();
            $trx->sempoa_id = $response['data']['id'];
            $trx->sempoa_type = 'App\Models\Transaction';
            $trx->save();
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), [
                'action' => 'Push to Jurnal Sempoa',
                'user' => auth()->user()->name,
                'data' => $data
            ]);
            $data->sempoas()->delete();
            $data->sempoa_processed = false;
            $data->save();
        }
    }
}
