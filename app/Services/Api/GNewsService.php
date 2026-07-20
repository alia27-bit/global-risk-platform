<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class GNewsService
{
    public function configured(): bool
    {
        $key = (string) config('services.gnews.key');

        return $key !== '' && ! str_starts_with($key, 'ISI_');
    }

    public function search(string $country): array
    {
        return $this->searchQuery('('.$country.') AND (logistics OR trade OR shipping OR economy)');
    }

    public function globalSupplyChain(): array
    {
        return $this->searchQuery('supply chain OR logistics OR global trade OR shipping');
    }

    private function searchQuery(string $query): array
    {
        if (! $this->configured()) {
            return [];
        }

        $url = rtrim(config('services.gnews.url', 'https://gnews.io/api/v4'), '/').'/search';
        $response = Http::acceptJson()->retry(2, 500, throw: false)->timeout(25)->get($url, [
            'q' => $query,
            'lang' => 'en',
            'max' => 10,
            'apikey' => config('services.gnews.key'),
        ]);

        if (! $response->successful()) {
            throw new RuntimeException('GNews merespons HTTP '.$response->status().'.');
        }

        return (array) $response->json('articles', []);
    }
}
