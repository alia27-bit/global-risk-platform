<?php

namespace App\Services\Api;

use App\Models\Country;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class NegaraService
{
    protected string $fallbackUrl = 'https://raw.githubusercontent.com/mledoze/countries/master/countries.json';

    public function sync(): int
    {
        $client = Http::acceptJson()->retry(2, 500, throw: false)->timeout(60);
        $countries = $this->countriesFromV5($client);

        if (! is_array($countries) || $countries === [] || ! array_is_list($countries)) {
            try {
                $fallback = $client->get($this->fallbackUrl);
                $countries = $fallback->successful() ? $fallback->json() : [];
            } catch (Throwable $exception) {
                Log::warning('Country dataset fallback synchronization failed.', [
                    'message' => $exception->getMessage(),
                ]);

                return 0;
            }
        }

        $synchronized = 0;
        $populations = $this->worldBankPopulations($client);

        foreach ($countries as $country) {
            if (! is_array($country)) {
                continue;
            }

            $code = data_get($country, 'cca3') ?? data_get($country, 'codes.alpha_3');
            $name = data_get($country, 'name.common') ?? data_get($country, 'names.common');

            if (! is_string($code) || $code === '' || ! is_string($name) || $name === '') {
                continue;
            }

            $currency = null;

            $currencies = data_get($country, 'currencies', []);

            if (is_array($currencies) && $currencies !== []) {
                $firstCurrencyKey = array_key_first($currencies);
                $currency = is_string($currencies[$firstCurrencyKey] ?? null)
                    ? $currencies[$firstCurrencyKey]
                    : $firstCurrencyKey;
            }

            $alpha2 = strtolower((string) (data_get($country, 'cca2') ?? data_get($country, 'codes.alpha_2')));

            Country::updateOrCreate(

                [
                    'code' => $code,
                ],

                [
                    'name' => $name,
                    'alpha2' => strtoupper($alpha2),
                    'capital' => data_get($country, 'capital.0') ?? data_get($country, 'capitals.0.name'),
                    'region' => data_get($country, 'region'),
                    'subregion' => data_get($country, 'subregion'),
                    'currency' => $currency ? (data_get($currencies, $currency.'.name') ?? $currency) : null,
                    'currency_code' => $currency,
                    'languages' => $this->languages($country),
                    'population' => data_get($country, 'population')
                        ?? data_get($country, 'economy.population')
                        ?? ($populations[$code] ?? 0),
                    'latitude' => data_get($country, 'latlng.0') ?? data_get($country, 'coordinates.latitude') ?? 0,
                    'longitude' => data_get($country, 'latlng.1') ?? data_get($country, 'coordinates.longitude') ?? 0,
                    'flag' => data_get($country, 'flags.png')
                        ?? data_get($country, 'flag.url_png')
                        ?? ($alpha2 ? "https://flagcdn.com/w320/{$alpha2}.png" : null),
                ]
            );

            $synchronized++;
        }

        return $synchronized;
    }

    private function countriesFromV5($client): array
    {
        $key = trim((string) config('services.restcountries.key'));
        if ($key === '') return [];

        $countries = [];
        $baseUrl = rtrim((string) config('services.restcountries.url'), '/');

        try {
            foreach ([0, 100, 200] as $offset) {
                $response = $client->withToken($key)->get($baseUrl, [
                    'limit' => 100,
                    'offset' => $offset,
                    'response_fields' => 'names.common,codes.alpha_2,codes.alpha_3,capitals,region,subregion,currencies,languages,population,coordinates,flag',
                ]);
                if (! $response->successful()) {
                    Log::warning('REST Countries v5 returned an unsuccessful response.', ['status' => $response->status()]);
                    return [];
                }
                $objects = data_get($response->json(), 'data.objects', []);
                if (! is_array($objects)) return [];
                $countries = array_merge($countries, $objects);
                if (count($objects) < 100) break;
            }
        } catch (Throwable $exception) {
            Log::warning('REST Countries v5 synchronization failed.', ['message' => $exception->getMessage()]);
            return [];
        }

        return $countries;
    }

    private function worldBankPopulations($client): array
    {
        try {
            $url = rtrim((string) config('services.worldbank.url'), '/')
                .'/country/all/indicator/SP.POP.TOTL';
            $response = $client->get($url, ['format' => 'json', 'per_page' => 400, 'mrnev' => 1]);
            if (! $response->successful()) return [];

            $populations = [];
            foreach ((array) data_get($response->json(), '1', []) as $row) {
                $code = data_get($row, 'countryiso3code');
                $value = data_get($row, 'value');
                if (is_string($code) && strlen($code) === 3 && is_numeric($value)) {
                    $populations[$code] = (int) $value;
                }
            }
            return $populations;
        } catch (Throwable $exception) {
            Log::warning('World Bank population enrichment failed.', ['message' => $exception->getMessage()]);
            return [];
        }
    }

    private function languages(array $country): array
    {
        $languages = data_get($country, 'languages', []);

        if (! is_array($languages)) {
            return [];
        }

        return array_values(array_filter($languages, 'is_string'));
    }
}
