<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;

class ExchangeRateService
{

    protected string $url='https://open.er-api.com/v6/latest/USD';

    public function getRate($currency)
    {

        $response=Http::get($this->url);

        if(!$response->successful()){
            return null;
        }

        $rates=$response->json()['rates'];

        return $rates[$currency] ?? null;

    }

}