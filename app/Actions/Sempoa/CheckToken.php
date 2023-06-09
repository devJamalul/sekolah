<?php

namespace App\Actions\Sempoa;

use Illuminate\Support\Facades\Http;

class CheckToken
{
    public static function run(string $token)
    {
        $response = Http::post(config('sempoa.base_url') . 'check', [
            'token' => $token
        ]);

        if ($response->badRequest()) {
            throw new \Exception($response->body());
        }
    }
}
