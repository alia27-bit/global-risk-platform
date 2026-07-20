<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\ApiLog;
use App\Models\ExchangeRate;
use App\Services\Api\ExchangeRateService;
use Throwable;

class ExchangeRateController extends Controller
{
    public function index()
    {
        $latestRateIds = ExchangeRate::query()
            ->selectRaw('MAX(id)')
            ->groupBy('country_id');

        $exchangeRates = ExchangeRate::query()
            ->whereIn('id', $latestRateIds)
            ->with('country')
            ->orderBy('target_currency')
            ->paginate(20);

        return view('exchange.index', compact('exchangeRates'));
    }

    public function show(Country $country)
    {
        $exchange = $country->exchangeRate;
        $history = $country->hasMany(ExchangeRate::class)->latest()->limit(30)->get()->reverse()->values();

        return view(
            'exchange.show',
            compact(
                'country',
                'exchange',
                'history'
            )
        );
    }

    public function sync(Country $country, ExchangeRateService $service)
    {
        if (! $country->currency_code) return back()->with('error', 'Kode mata uang negara belum tersedia.');

        try {
            $rate = $service->getRate($country->currency_code);
            if ($rate === null) return back()->with('error', 'Kurs mata uang tidak tersedia dari ExchangeRate API.');

            ExchangeRate::create([
                'country_id' => $country->id,
                'base_currency' => 'USD',
                'target_currency' => $country->currency_code,
                'exchange_rate' => $rate,
            ]);
            ApiLog::create(['api_name' => 'ExchangeRate', 'status_code' => 200, 'message' => "USD/{$country->currency_code} synchronized"]);
            return back()->with('success', 'Nilai tukar real-time berhasil disimpan.');
        } catch (Throwable $exception) {
            ApiLog::create(['api_name' => 'ExchangeRate', 'status_code' => 500, 'message' => $exception->getMessage()]);
            return back()->with('error', 'Koneksi ExchangeRate API gagal: '.$exception->getMessage());
        }
    }
}
