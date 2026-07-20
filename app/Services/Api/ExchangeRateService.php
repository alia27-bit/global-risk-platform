<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;

class ExchangeRateService
{
    public function getRate(string $currency, string $base = 'USD'): ?float
    {
        $url = rtrim(config('services.exchange.url', 'https://open.er-api.com/v6/latest'), '/').'/'.strtoupper($base);
        $response = Http::acceptJson()->retry(2, 400)->timeout(20)->get($url);

        if (! $response->successful() || $response->json('result') === 'error') {
            return null;
        }

        $rate = $response->json('rates.'.strtoupper($currency));

        return is_numeric($rate) ? (float) $rate : null;
    }
}
