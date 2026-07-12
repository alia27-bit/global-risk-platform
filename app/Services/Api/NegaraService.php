<?php

namespace App\Services\Api;

use App\Models\Negara;
use Illuminate\Support\Facades\Http;

class NegaraService
{
    protected string $baseUrl = 'https://restcountries.com/v3.1';

    public function sync()
    {
        $response = Http::timeout(60)->get($this->baseUrl . '/all');

        if (!$response->successful()) {
            return false;
        }

        foreach ($response->json() as $country) {

            $currency = null;

            if (isset($country['currencies'])) {
                $currency = array_key_first($country['currencies']);
            }

            Negara::updateOrCreate(

                [
                    'kode_iso3' => $country['cca3']
                ],

                [
                    'nama_negara' => $country['name']['common'] ?? '',
                    'kode_iso2' => $country['cca2'] ?? '',
                    'ibukota' => $country['capital'][0] ?? '',
                    'wilayah' => $country['region'] ?? '',
                    'sub_wilayah' => $country['subregion'] ?? '',
                    'mata_uang' => $currency,
                    'populasi' => $country['population'] ?? 0,
                    'latitude' => $country['latlng'][0] ?? 0,
                    'longitude' => $country['latlng'][1] ?? 0,
                    'bendera' => $country['flags']['png'] ?? '',
                ]
            );

        }

        return true;
    }
}