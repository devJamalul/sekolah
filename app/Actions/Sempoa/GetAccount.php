<?php

namespace App\Actions\Sempoa;

use App\Models\SempoaConfiguration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetAccount
{
    public static function run()
    {
        $config = SempoaConfiguration::first();
        $response = Http::withToken($config->token)
            ->get(config('sempoa.base_url') . 'akun');

        if ($response->notFound()) {
            $kode = null;
            throw new \Exception($response['message']);
        }

        return $response['data'];
    }
}
