<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;

class OpenMeteoService
{
    public function current(float $latitude, float $longitude): ?array
    {
        $response = Http::acceptJson()->retry(2, 400)->timeout(20)->get(
            config('services.openmeteo.url', 'https://api.open-meteo.com/v1/forecast'),
            [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'current' => 'temperature_2m,rain,precipitation,weather_code,wind_speed_10m,wind_gusts_10m',
                'timezone' => 'auto',
            ]
        );

        if (! $response->successful() || ! is_array($response->json('current'))) {
            return null;
        }

        return $response->json('current');
    }
}
