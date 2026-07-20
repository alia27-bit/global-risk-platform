<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\ApiLog;
use App\Models\Weather;
use App\Services\Api\OpenMeteoService;
use Throwable;

class WeatherController extends Controller
{
    public function map()
    {
        return view('weather.map');
    }

    public function show(Country $country)
    {
        $weather = $country->weather;
        $history = $country->hasMany(Weather::class)->latest('observed_at')->limit(30)->get()->reverse()->values();

        return view('weather.show', compact(
            'country',
            'weather',
            'history'
        ));
    }

    public function sync(Country $country, OpenMeteoService $service)
    {
        try {
            $current = $service->current((float) $country->latitude, (float) $country->longitude);
            if (! $current) {
                ApiLog::create(['api_name' => 'Open-Meteo', 'status_code' => 502, 'message' => "No weather data for {$country->code}"]);
                return back()->with('error', 'Open-Meteo tidak mengembalikan data cuaca.');
            }

            Weather::create([
                'country_id' => $country->id,
                'temperature' => $current['temperature_2m'] ?? null,
                'wind_speed' => $current['wind_speed_10m'] ?? null,
                'weather_code' => $current['weather_code'] ?? null,
                'rainfall' => $current['rain'] ?? $current['precipitation'] ?? null,
                'storm_risk' => min(100, (($current['wind_gusts_10m'] ?? 0) * 1.25) + (($current['weather_code'] ?? 0) >= 95 ? 40 : 0)),
                'observed_at' => $current['time'] ?? now(),
            ]);
            ApiLog::create(['api_name' => 'Open-Meteo', 'status_code' => 200, 'message' => "Weather synchronized for {$country->code}"]);
            return back()->with('success', 'Data cuaca berhasil disinkronkan dari Open-Meteo.');
        } catch (Throwable $exception) {
            ApiLog::create(['api_name' => 'Open-Meteo', 'status_code' => 500, 'message' => $exception->getMessage()]);
            return back()->with('error', 'Koneksi Open-Meteo gagal: '.$exception->getMessage());
        }
    }
}
