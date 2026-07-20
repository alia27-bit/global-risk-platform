<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\RiskScore;
use App\Services\MonitoringSyncService;

class RiskController extends Controller
{
    public function index()
    {
        $latestIds = RiskScore::query()->selectRaw('MAX(id)')->groupBy('country_id');
        $latest = RiskScore::query()->whereIn('id', $latestIds);
        $riskScores = (clone $latest)->with('country')
            ->orderByDesc('total_score')
            ->paginate(20);

        $countries = Country::orderBy('name')->get(['id', 'name', 'code']);
        $summary = [
            'total' => (clone $latest)->count(),
            'low' => (clone $latest)->where('category', 'Low')->count(),
            'medium' => (clone $latest)->where('category', 'Medium')->count(),
            'high' => (clone $latest)->where('category', 'High')->count(),
        ];

        return view(
            'risk.index',
            compact('riskScores', 'countries', 'summary')
        );
    }

    public function show(Country $country)
    {
        $risk = RiskScore::where('country_id', $country->id)->latest()->first();

        $country->load(['weather', 'economicIndicator', 'exchangeRate']);
        $history = $country->hasMany(RiskScore::class)->latest()->limit(30)->get()->reverse()->values();

        return view(
            'risk.show',
            compact(
                'country',
                'risk',
                'history'
            )
        );
    }

    public function calculate(Country $country, \App\Services\RiskScoringService $scoring, MonitoringSyncService $sync)
    {
        if (! $country->weather) $sync->weather($country);
        if (! $country->economicIndicator) $sync->economy($country);
        if (! $country->exchangeRate) $sync->exchange($country);

        $country->refresh()->load(['weather', 'economicIndicator', 'exchangeRate']);
        $weather = $country->weather;
        $economic = $country->economicIndicator;
        $exchange = $country->exchangeRate;

        $previousRate = $country->hasMany(\App\Models\ExchangeRate::class)->latest()->skip(1)->value('exchange_rate');
        $weatherScore = $scoring->weatherScore($weather?->wind_speed, $weather?->rainfall, $weather?->storm_risk);
        $economicScore = $scoring->inflationScore($economic?->inflation);
        $currencyScore = $scoring->currencyScore($exchange?->exchange_rate, $previousRate === null ? null : (float) $previousRate);

        $sentiments = $country->news()->with('sentimentAnalysis')->get()
            ->pluck('sentimentAnalysis.result')->filter();
        $newsScore = $scoring->newsScore($sentiments);

        $total = $scoring->calculate($weatherScore, $economicScore, $currencyScore, $newsScore);

        RiskScore::create(
            [
                'country_id' => $country->id,
                'weather_score' => $weatherScore,
                'economic_score' => $economicScore,
                'currency_score' => $currencyScore,
                'news_score' => $newsScore,
                'total_score' => $total,
                'category' => $scoring->category($total),
            ]
        );

        return back()->with(
            'success',
            __('messages.risk_calculated')
        );
    }
}
