<?php

namespace App\Http\Controllers;

use App\Models\ApiLog;
use App\Models\Country;
use App\Models\EconomicIndicator;
use App\Models\ExchangeRate;
use App\Models\News;
use App\Models\Port;
use App\Models\RiskScore;
use App\Models\User;
use App\Models\Watchlist;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->peran === 'admin') {
            $latestRiskIds = RiskScore::query()->selectRaw('MAX(id)')->groupBy('country_id');

            $topCountries = Country::with('economicIndicator')->limit(10)->get();

            return view('dashboard.admin', [
                'countries' => Country::count(),
                'ports' => Port::count(),
                'newsCount' => News::count(),
                'users' => User::count(),
                'riskSummary' => RiskScore::whereIn('id', $latestRiskIds)
                    ->selectRaw('category, COUNT(*) total')
                    ->groupBy('category')
                    ->pluck('total', 'category'),
                'topRisks' => RiskScore::whereIn('id', $latestRiskIds)
                    ->with('country')
                    ->orderByDesc('total_score')
                    ->limit(10)
                    ->get(),
                'apiLogs' => ApiLog::latest()->limit(10)->get(),
                'riskChart' => RiskScore::whereIn('id', $latestRiskIds)
                    ->with('country')
                    ->orderByDesc('total_score')
                    ->limit(10)
                    ->get(),
                'gdpChart' => $topCountries,
                'inflationChart' => $topCountries,
                'currencyChart' => Country::with('exchangeRate')->limit(10)->get(),
                'riskTrendChart' => RiskScore::whereIn('id', $latestRiskIds)
                    ->with('country')
                    ->orderByDesc('total_score')
                    ->limit(10)
                    ->get(),
            ]);
        }

        $watchlists = Watchlist::with('country.riskScore')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->limit(8)
            ->get();

        $countryIds = $watchlists->pluck('country_id')->filter()->values();

        return view('dashboard.user', [
            'watchlists' => $watchlists,
            'watchlistCount' => Watchlist::where('user_id', $request->user()->id)->count(),
            'countries' => Country::count(),
            'latestNews' => News::with(['country', 'sentimentAnalysis'])->latest('published_at')->limit(5)->get(),
            'riskChart' => $watchlists
                ->filter(fn ($item) => $item->country?->riskScore)
                ->map(fn ($item) => [
                    'country' => $item->country->name,
                    'score' => (float) $item->country->riskScore->total_score,
                    'category' => $item->country->riskScore->category,
                ])
                ->values(),
            'riskCategory' => [
                'High' => $watchlists->filter(fn ($item) => optional($item->country->riskScore)->category == 'High')->count(),
                'Medium' => $watchlists->filter(fn ($item) => optional($item->country->riskScore)->category == 'Medium')->count(),
                'Low' => $watchlists->filter(fn ($item) => optional($item->country->riskScore)->category == 'Low')->count(),
            ],
            'trendCharts' => $this->trendCharts($countryIds),
        ]);
    }

    private function trendCharts($countryIds): array
    {
        if ($countryIds->isEmpty()) {
            return [
                'labels' => [],
                'gdp' => [],
                'inflation' => [],
                'currency' => [],
                'risk' => [],
            ];
        }

        $labels = collect();
        $gdp = collect();
        $inflation = collect();
        $currency = collect();
        $risk = collect();

        foreach ($countryIds as $countryId) {
            $country = Country::find($countryId);
            if (! $country) {
                continue;
            }

            $labels->push($country->name);
            $gdp->push((float) (EconomicIndicator::where('country_id', $countryId)->latest('id')->value('gdp') ?? 0));
            $inflation->push((float) (EconomicIndicator::where('country_id', $countryId)->latest('id')->value('inflation') ?? 0));
            $currency->push((float) (ExchangeRate::where('country_id', $countryId)->latest('id')->value('exchange_rate') ?? 0));
            $risk->push((float) (RiskScore::where('country_id', $countryId)->latest('id')->value('total_score') ?? 0));
        }

        return [
            'labels' => $labels->values()->all(),
            'gdp' => $gdp->values()->all(),
            'inflation' => $inflation->values()->all(),
            'currency' => $currency->values()->all(),
            'risk' => $risk->values()->all(),
            'history' => [
                'gdp' => $this->historySeries(EconomicIndicator::class, $countryIds, 'gdp'),
                'inflation' => $this->historySeries(EconomicIndicator::class, $countryIds, 'inflation'),
                'currency' => $this->historySeries(ExchangeRate::class, $countryIds, 'exchange_rate'),
                'risk' => $this->historySeries(RiskScore::class, $countryIds, 'total_score'),
            ],
        ];
    }

    private function historySeries(string $model, $countryIds, string $field): array
    {
        $records = $model::query()
            ->whereIn('country_id', $countryIds)
            ->orderBy('created_at')
            ->get(['country_id', $field, 'created_at']);

        if ($records->isEmpty()) {
            return ['labels' => ['No data'], 'datasets' => []];
        }

        $labels = $records->pluck('created_at')->map(fn ($date) => $date->format('d M'))->unique()->values()->all();
        $datasets = [];

        foreach ($countryIds as $countryId) {
            $countryName = Country::find($countryId)?->name ?? 'Country';
            $countryRecords = $records->where('country_id', $countryId)->values();
            $datasets[] = [
                'label' => $countryName,
                'data' => $countryRecords->map(fn ($row) => (float) $row->{$field})->values()->all(),
            ];
        }

        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];
    }
}
