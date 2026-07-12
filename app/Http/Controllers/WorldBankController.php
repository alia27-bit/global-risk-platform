<?php

namespace App\Http\Controllers;

use App\Models\Negara;
use App\Models\IndikatorEkonomi;
use App\Services\Api\WorldBankService;

class WorldBankController extends Controller
{
    protected $service;

    public function __construct(WorldBankService $service)
    {
        $this->service = $service;
    }

    public function sync($countryId)
    {
        $country = Negara::findOrFail($countryId);

        $gdp = $this->service->indicator(
            strtolower($country->kode_iso2),
            'NY.GDP.MKTP.CD'
        );

        $inflation = $this->service->indicator(
            strtolower($country->kode_iso2),
            'FP.CPI.TOTL.ZG'
        );

        $population = $this->service->indicator(
            strtolower($country->kode_iso2),
            'SP.POP.TOTL'
        );

        IndikatorEkonomi::updateOrCreate(

            [
                'negara_id' => $country->id
            ],

            [
                'gdp' => data_get($gdp, '1.0.value'),
                'inflasi' => data_get($inflation, '1.0.value'),
                'populasi' => data_get($population, '1.0.value')
            ]

        );

        return back()->with('success', 'Data ekonomi berhasil diperbarui.');
    }
}