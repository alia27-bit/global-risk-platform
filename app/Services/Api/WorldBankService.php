<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;

class WorldBankService
{
    public function latestValue(string $countryCode, string $indicator): ?float
    {
        $url = rtrim(config('services.worldbank.url', 'https://api.worldbank.org/v2'), '/');
        $response = Http::acceptJson()->retry(2, 400)->timeout(25)->get(
            "{$url}/country/{$countryCode}/indicator/{$indicator}",
            ['format' => 'json', 'mrv' => 10, 'per_page' => 10]
        );

        if (! $response->successful()) {
            return null;
        }

        foreach ((array) $response->json('1', []) as $record) {
            if (is_array($record) && is_numeric($record['value'] ?? null)) {
                return (float) $record['value'];
            }
        }

        return null;
    }

    public function indicator(string $countryCode, string $indicator): array
    {
        $value = $this->latestValue($countryCode, $indicator);

        return $value === null ? [] : ['value' => $value];
    }
}
