<?php

namespace App\Http\Controllers;

use App\Http\Resources\CountryResource;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\EconomicResource;
use App\Http\Resources\NewsResource;
use App\Http\Resources\PortResource;
use App\Http\Resources\RiskScoreResource;
use App\Http\Resources\WeatherResource;
use App\Models\Country;
use App\Models\EconomicIndicator;
use App\Models\ExchangeRate;
use App\Models\News;
use App\Models\Port;
use App\Models\RiskScore;
use App\Models\Weather;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function dashboard()
    {
        $latestRiskIds = RiskScore::query()->selectRaw('MAX(id)')->groupBy('country_id');
        return response()->json([
            'countries' => Country::count(),
            'risk' => [
                'low' => RiskScore::whereIn('id', $latestRiskIds)->where('category', 'Low')->count(),
                'medium' => RiskScore::whereIn('id', $latestRiskIds)->where('category', 'Medium')->count(),
                'high' => RiskScore::whereIn('id', $latestRiskIds)->where('category', 'High')->count(),
            ],
            'ports' => Port::count(),
            'news' => News::count(),
        ]);
    }

    public function live()
    {
        $latestWeatherIds = Weather::query()->selectRaw('MAX(id)')->groupBy('country_id');
        $weather = Weather::with('country:id,name,code,latitude,longitude')
            ->whereIn('id', $latestWeatherIds)->get()->map(fn (Weather $item) => [
                'country' => $item->country?->name,
                'code' => $item->country?->code,
                'lat' => (float) $item->country?->latitude,
                'lng' => (float) $item->country?->longitude,
                'temperature' => (float) $item->temperature,
                'rainfall' => (float) $item->rainfall,
                'wind_speed' => (float) $item->wind_speed,
                'weather_code' => (int) $item->weather_code,
                'storm_risk' => (float) $item->storm_risk,
                'observed_at' => $item->observed_at?->toIso8601String() ?? $item->updated_at?->toIso8601String(),
                'url' => $item->country ? route('weather.show', $item->country) : null,
            ]);

        return response()->json([
            'server_time' => now()->toIso8601String(),
            'counts' => ['countries' => Country::count(), 'ports' => Port::count(), 'news' => News::count()],
            'weather' => $weather,
        ]);
    }

    public function countries(Request $request)
    {
        $query = Country::query()
            ->when($request->string('search')->toString(), fn (Builder $query, string $search) =>
                $query->where(fn (Builder $query) => $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")))
            ->when($request->string('region')->toString(), fn (Builder $query, string $region) =>
                $query->where('region', $region));

        return CountryResource::collection($query->orderBy('name')->paginate($this->perPage($request)));
    }

    public function country(Country $country)
    {
        return new CountryResource($country->load([
            'riskScore', 'weather', 'exchangeRate', 'economicIndicator', 'news.sentimentAnalysis', 'ports',
        ]));
    }

    public function weather(Request $request)
    {
        return WeatherResource::collection(Weather::with('country')->latest()->paginate($this->perPage($request)));
    }

    public function weatherDetail(Country $country)
    {
        return $country->weather
            ? new WeatherResource($country->weather->load('country'))
            : response()->json(['message' => 'Weather data is not available.'], 404);
    }

    public function economicIndicators(Request $request)
    {
        return EconomicResource::collection(EconomicIndicator::with('country')->latest()->paginate($this->perPage($request)));
    }

    public function economicDetail(Country $country)
    {
        return $country->economicIndicator
            ? new EconomicResource($country->economicIndicator->load('country'))
            : response()->json(['message' => 'Economic data is not available.'], 404);
    }

    public function exchangeRates(Request $request)
    {
        return CurrencyResource::collection(ExchangeRate::with('country')->latest()->paginate($this->perPage($request)));
    }

    public function exchangeDetail(Country $country)
    {
        return $country->exchangeRate
            ? new CurrencyResource($country->exchangeRate->load('country'))
            : response()->json(['message' => 'Exchange-rate data is not available.'], 404);
    }

    public function news(Request $request)
    {
        $query = News::with(['country', 'sentimentAnalysis'])->latest('published_at');
        if ($sentiment = $request->string('sentiment')->toString()) {
            $query->whereHas('sentimentAnalysis', fn (Builder $query) => $query->where('result', $sentiment));
        }

        return NewsResource::collection($query->paginate($this->perPage($request, 20)));
    }

    public function newsDetail(Country $country, Request $request)
    {
        return NewsResource::collection($country->news()->with('sentimentAnalysis')->latest('published_at')->paginate($this->perPage($request, 20)));
    }

    public function ports(Request $request)
    {
        $query = Port::with('country')->when($request->string('search')->toString(), fn (Builder $query, string $search) =>
            $query->where('name', 'like', "%{$search}%")
                ->orWhereHas('country', fn (Builder $query) => $query->where('name', 'like', "%{$search}%")));

        return PortResource::collection($query->orderBy('name')->paginate($this->perPage($request)));
    }

    public function portDetail(Country $country)
    {
        return PortResource::collection($country->ports()->with('country')->orderBy('name')->get());
    }

    public function riskScores(Request $request)
    {
        $latestIds = RiskScore::query()->selectRaw('MAX(id)')->groupBy('country_id');
        $query = RiskScore::whereIn('id', $latestIds)->with('country')
            ->when($request->string('category')->toString(), fn (Builder $query, string $category) => $query->where('category', $category));

        return RiskScoreResource::collection($query->orderByDesc('total_score')->paginate($this->perPage($request)));
    }

    public function riskDetail(Country $country)
    {
        return $country->riskScore
            ? new RiskScoreResource($country->riskScore->load('country'))
            : response()->json(['message' => 'Risk score is not available.'], 404);
    }

    private function perPage(Request $request, int $default = 25): int
    {
        return min(100, max(1, $request->integer('per_page', $default)));
    }
}
