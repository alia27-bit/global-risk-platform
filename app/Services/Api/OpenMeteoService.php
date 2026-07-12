<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;

class OpenMeteoService
{

    protected string $url = 'https://api.open-meteo.com/v1/forecast';

    public function current($latitude,$longitude)
    {

        $response = Http::get($this->url,[

            'latitude'=>$latitude,
            'longitude'=>$longitude,
            'current'=>[
                'temperature_2m',
                'rain',
                'wind_speed_10m'
            ]

        ]);

        if(!$response->successful()){
            return null;
        }

        return $response->json();

    }

}