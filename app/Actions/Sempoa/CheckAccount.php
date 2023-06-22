<?php

namespace App\Actions\Sempoa;

use App\Models\SempoaConfiguration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckAccount
{
    public static function run(string $kode)
    {
        $config = SempoaConfiguration::first();
        $response = Http::withToken($config->token)
            ->get(config('sempoa.base_url') . 'akun/' . $kode);

        if ($response->notFound()) {
            $kode = null;
            throw new \Exception($response['message']);
        }

        return $kode;
    }
}
