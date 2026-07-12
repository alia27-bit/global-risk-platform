<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;

class WorldBankService
{

    protected string $url='https://api.worldbank.org/v2';

    public function indicator($country,$indicator)
    {

        $response=Http::get($this->url."/country/{$country}/indicator/{$indicator}",[
            'format'=>'json'
        ]);

        if(!$response->successful()){
            return [];
        }

        return $response->json();

    }

}