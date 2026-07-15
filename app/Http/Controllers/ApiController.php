<?php

namespace App\Http\Controllers;

use App\Models\Negara;
use App\Models\Berita;
use App\Models\Pelabuhan;
use App\Models\SkorRisiko;
use App\Models\NilaiTukar;
use App\Models\DataCuaca;
use App\Models\IndikatorEkonomi;
use App\Http\Resources\CountryResource;
use App\Http\Resources\RiskScoreResource;
use App\Http\Resources\NewsResource;
use App\Http\Resources\WeatherResource;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\EconomicResource;
use App\Http\Resources\PortResource;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * GET /api/v1/countries
     * List all countries with optional search & pagination
     */
    public function countries(Request $request)
    {
        $query = Negara::query();

        if ($search = $request->query('search')) {
            $query->where('nama_negara', 'like', "%{$search}%")
                  ->orWhere('kode_iso2', 'like', "%{$search}%")
                  ->orWhere('kode_iso3', 'like', "%{$search}%");
        }

        if ($region = $request->query('region')) {
            $query->where('wilayah', $region);
        }

        $perPage = min($request->query('per_page', 25), 100);

        return CountryResource::collection(
            $query->orderBy('nama_negara')->paginate($perPage)
        );
    }

    /**
     * GET /api/v1/countries/{id}
     * Get a single country with all related data
     */
    public function countryDetail($id)
    {
        $country = Negara::with([
            'skorRisiko',
            'dataCuaca',
            'nilaiTukar',
            'indikatorEkonomi',
            'berita' => fn($q) => $q->latest()->take(5),
        ])->findOrFail($id);

        return new CountryResource($country);
    }

    /**
     * GET /api/v1/risk-scores
     * List all risk scores with country info
     */
    public function riskScores(Request $request)
    {
        $query = SkorRisiko::with('negara');

        if ($category = $request->query('category')) {
            $query->where('kategori', $category);
        }

        $query->orderByDesc('total_skor');

        $perPage = min($request->query('per_page', 25), 100);

        return RiskScoreResource::collection($query->paginate($perPage));
    }

    /**
     * GET /api/v1/countries/{id}/risk
     */
    public function countryRisk($id)
    {
        $risk = SkorRisiko::where('negara_id', $id)->first();

        if (!$risk) {
            return response()->json([
                'message' => 'No risk score data available for this country.'
            ], 404);
        }

        return new RiskScoreResource($risk);
    }

    /**
     * GET /api/v1/countries/{id}/weather
     */
    public function countryWeather($id)
    {
        $weather = DataCuaca::where('negara_id', $id)->latest()->first();

        if (!$weather) {
            return response()->json([
                'message' => 'No weather data available for this country.'
            ], 404);
        }

        return new WeatherResource($weather);
    }

    /**
     * GET /api/v1/countries/{id}/currency
     */
    public function countryCurrency($id)
    {
        $currency = NilaiTukar::where('negara_id', $id)->latest()->first();

        if (!$currency) {
            return response()->json([
                'message' => 'No currency data available for this country.'
            ], 404);
        }

        return new CurrencyResource($currency);
    }

    /**
     * GET /api/v1/countries/{id}/economy
     */
    public function countryEconomy($id)
    {
        $economy = IndikatorEkonomi::where('negara_id', $id)->latest()->first();

        if (!$economy) {
            return response()->json([
                'message' => 'No economic data available for this country.'
            ], 404);
        }

        return new EconomicResource($economy);
    }

    /**
     * GET /api/v1/countries/{id}/news
     */
    public function countryNews($id)
    {
        $news = Berita::where('negara_id', $id)
            ->latest('published_at')
            ->paginate(20);

        return NewsResource::collection($news);
    }

    /**
     * GET /api/v1/news
     * List all news globally
     */
    public function news(Request $request)
    {
        $query = Berita::with('negara')->latest('published_at');

        if ($sentiment = $request->query('sentiment')) {
            $query->where('sentiment', $sentiment);
        }

        $perPage = min($request->query('per_page', 20), 100);

        return NewsResource::collection($query->paginate($perPage));
    }

    /**
     * GET /api/v1/ports
     */
    public function ports(Request $request)
    {
        $query = Pelabuhan::query();

        if ($search = $request->query('search')) {
            $query->where('nama_pelabuhan', 'like', "%{$search}%")
                  ->orWhere('kota', 'like', "%{$search}%");
        }

        $perPage = min($request->query('per_page', 25), 100);

        return PortResource::collection($query->paginate($perPage));
    }
}