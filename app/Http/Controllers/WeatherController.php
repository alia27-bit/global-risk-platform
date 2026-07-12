<?php

namespace App\Http\Controllers;

use App\Models\Negara;
use App\Models\DataCuaca;
use App\Services\Api\OpenMeteoService;

class WeatherController extends Controller
{
    protected $service;

    public function __construct(OpenMeteoService $service)
    {
        $this->service = $service;
    }

    public function sync($id)
    {
        $country = Negara::findOrFail($id);

        $weather = $this->service->current(
            $country->latitude,
            $country->longitude
        );

        if (!$weather) {
            return back()->with('error', 'Gagal mengambil data cuaca.');
        }

        DataCuaca::updateOrCreate(
            [
                'negara_id' => $country->id
            ],
            [
                'temperatur' => $weather['current']['temperature_2m'] ?? 0,
                'curah_hujan' => $weather['current']['rain'] ?? 0,
                'kecepatan_angin' => $weather['current']['wind_speed_10m'] ?? 0,
                'risiko_badai' => 0
            ]
        );

        return back()->with('success', 'Data cuaca berhasil diperbarui.');
    }
}