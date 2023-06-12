<?php

namespace App\Actions\Sempoa;

use Illuminate\Support\Facades\Http;

class CheckToken
{
    public static function run(string $token)
    {
        $response = Http::withToken($token)
        ->post(config('sempoa.base_url') . 'check');

        if (!$response->ok()) {
            throw new \Exception($response->body());
        }
    }
}
