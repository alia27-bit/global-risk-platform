<?php

namespace App\Services\Api;

use App\Models\Country;
use App\Models\Port;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class WorldPortService
{
    public function sync(): int
    {
        $url = (string) config('services.world_ports.url');
        if ($url === '') {
            $url = 'https://services-eu1.arcgis.com/BuS9rtTsYEV5C0xh/arcgis/rest/services/World_Port_Index/FeatureServer/0/query';
        }
        $countries = Country::whereNotNull('alpha2')->get()->keyBy(fn (Country $country) => strtoupper($country->alpha2));
        $saved = 0;

        for ($offset = 0; $offset < 10000; $offset += 2000) {
            $response = Http::acceptJson()->retry(2, 500, throw: false)->timeout(90)->get($url, [
                'where' => '1=1', 'outFields' => 'PORT_NAME,COUNTRY,LATITUDE,LONGITUDE,HARBORSIZE',
                'outSR' => 4326, 'resultOffset' => $offset, 'resultRecordCount' => 2000,
                'orderByFields' => 'OBJECTID', 'f' => 'json',
            ]);
            if (! $response->successful() || $response->json('error')) {
                throw new RuntimeException('World Port Index gagal merespons (HTTP '.$response->status().').');
            }
            $features = (array) $response->json('features', []);
            foreach ($features as $feature) {
                $attributes = (array) data_get($feature, 'attributes', []);
                $country = $countries->get(strtoupper((string) ($attributes['COUNTRY'] ?? '')));
                $name = trim((string) ($attributes['PORT_NAME'] ?? ''));
                if (! $country || $name === '') continue;
                Port::updateOrCreate(['country_id' => $country->id, 'name' => $name], [
                    'latitude' => $attributes['LATITUDE'] ?? data_get($feature, 'geometry.y'),
                    'longitude' => $attributes['LONGITUDE'] ?? data_get($feature, 'geometry.x'),
                    'port_type' => trim((string) ($attributes['HARBORSIZE'] ?? '')) ?: null,
                ]);
                $saved++;
            }
            if (! $response->json('exceededTransferLimit', false) || $features === []) break;
        }

        return $saved;
    }
}
