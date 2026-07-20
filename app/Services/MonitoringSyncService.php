<?php

namespace App\Services;

use App\Models\ApiLog;
use App\Models\Country;
use App\Models\EconomicIndicator;
use App\Models\ExchangeRate;
use App\Models\News;
use App\Models\RiskScore;
use App\Models\SentimentAnalysis;
use App\Models\Weather;
use App\Services\Api\ExchangeRateService;
use App\Services\Api\GNewsService;
use App\Services\Api\NegaraService;
use App\Services\Api\OpenMeteoService;
use App\Services\Api\WorldBankService;
use App\Services\Api\WorldPortService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Throwable;

class MonitoringSyncService
{
    public function countries(): int
    {
        return app(NegaraService::class)->sync();
    }

    public function ports(): int
    {
        try {
            $saved = app(WorldPortService::class)->sync();
            $this->log('World Port Index', 200, $saved.' ports synchronized');

            return $saved;
        } catch (Throwable $e) {
            $this->log('World Port Index', 500, 'Scheduled sync failed: '.$e->getMessage());

            return 0;
        }
    }

    public function monitoredCountries(): Collection
    {
        $countries = Country::whereHas('watchlists')->orderBy('name')->limit(25)->get();

        return $countries->isNotEmpty()
            ? $countries
            : Country::where('code', 'IDN')->get();
    }

    public function weather(Country $country): bool
    {
        try {
            $data = app(OpenMeteoService::class)->current((float) $country->latitude, (float) $country->longitude);
            if (! $data) return false;
            Weather::create([
                'country_id' => $country->id,
                'temperature' => $data['temperature_2m'] ?? null,
                'wind_speed' => $data['wind_speed_10m'] ?? null,
                'weather_code' => $data['weather_code'] ?? null,
                'rainfall' => $data['rain'] ?? $data['precipitation'] ?? null,
                'storm_risk' => min(100, (($data['wind_gusts_10m'] ?? 0) * 1.25) + (($data['weather_code'] ?? 0) >= 95 ? 40 : 0)),
                'observed_at' => $data['time'] ?? now(),
            ]);
            return true;
        } catch (Throwable $e) {
            $this->log('Open-Meteo', 500, $country->code.' scheduled sync failed: '.$e->getMessage());
            return false;
        }
    }

    public function economy(Country $country): bool
    {
        try {
            $api = app(WorldBankService::class);
            $gdp = $api->latestValue($country->code, 'NY.GDP.MKTP.CD');
            $inflation = $api->latestValue($country->code, 'FP.CPI.TOTL.ZG');
            $unemployment = $api->latestValue($country->code, 'SL.UEM.TOTL.ZS');
            $exports = $api->latestValue($country->code, 'NE.EXP.GNFS.CD');
            $imports = $api->latestValue($country->code, 'NE.IMP.GNFS.CD');
            $population = $api->latestValue($country->code, 'SP.POP.TOTL');
            if ($gdp === null && $inflation === null && $unemployment === null) return false;
            EconomicIndicator::create(['country_id' => $country->id] + compact('gdp', 'inflation', 'unemployment', 'exports', 'imports'));
            if ($population !== null) $country->update(['population' => (int) $population]);
            return true;
        } catch (Throwable $e) {
            $this->log('World Bank', 500, $country->code.' scheduled sync failed: '.$e->getMessage());
            return false;
        }
    }

    public function exchange(Country $country): bool
    {
        if (! $country->currency_code) return false;
        try {
            $rate = app(ExchangeRateService::class)->getRate($country->currency_code);
            if ($rate === null) return false;
            $latest = $country->exchangeRate;
            if (! $latest || abs((float) $latest->exchange_rate - $rate) > 0.000001 || $latest->created_at->lt(now()->subMinutes(15))) {
                ExchangeRate::create(['country_id' => $country->id, 'base_currency' => 'USD', 'target_currency' => $country->currency_code, 'exchange_rate' => $rate]);
            }
            return true;
        } catch (Throwable $e) {
            $this->log('ExchangeRate', 500, $country->code.' scheduled sync failed: '.$e->getMessage());
            return false;
        }
    }

    public function news(): int
    {
        try {
            $articles = app(GNewsService::class)->globalSupplyChain();
            $saved = 0;
            foreach ($articles as $article) {
                if (! is_array($article) || empty($article['title']) || empty($article['url'])) continue;
                $news = News::updateOrCreate(['url' => $article['url']], [
                    'country_id' => null,
                    'title' => $article['title'],
                    'content' => $article['content'] ?? $article['description'] ?? null,
                    'source' => data_get($article, 'source.name'),
                    'published_at' => ! empty($article['publishedAt']) ? Carbon::parse($article['publishedAt']) : now(),
                ]);
                $result = app(SentimentService::class)->analyze($news->title.' '.$news->content);
                SentimentAnalysis::updateOrCreate(['news_id' => $news->id], [
                    'positive' => $result['positive'], 'negative' => $result['negative'],
                    'neutral' => $result['neutral'],
                    'result' => $result['label'],
                ]);
                $saved++;
            }
            return $saved;
        } catch (Throwable $e) {
            $this->log('GNews', 500, 'Scheduled sync failed: '.$e->getMessage());
            return 0;
        }
    }

    public function risk(Country $country): bool
    {
        $country->load(['weather', 'economicIndicator', 'exchangeRate', 'news.sentimentAnalysis']);
        $scoring = app(RiskScoringService::class);
        $previousRate = $country->hasMany(ExchangeRate::class)->latest()->skip(1)->value('exchange_rate');
        $weatherScore = $scoring->weatherScore($country->weather?->wind_speed, $country->weather?->rainfall, $country->weather?->storm_risk);
        $economicScore = $scoring->inflationScore($country->economicIndicator?->inflation);
        $currencyScore = $scoring->currencyScore($country->exchangeRate?->exchange_rate, $previousRate === null ? null : (float) $previousRate);
        $sentiments = $country->news->pluck('sentimentAnalysis.result')->filter();
        $newsScore = $scoring->newsScore($sentiments);
        $total = $scoring->calculate($weatherScore, $economicScore, $currencyScore, $newsScore);
        RiskScore::create([
            'country_id' => $country->id,
            'weather_score' => $weatherScore, 'economic_score' => $economicScore,
            'currency_score' => $currencyScore, 'news_score' => $newsScore,
            'total_score' => $total, 'category' => $scoring->category($total),
        ]);
        return true;
    }

    private function log(string $api, int $status, string $message): void
    {
        ApiLog::create(['api_name' => $api, 'status_code' => $status, 'message' => mb_substr($message, 0, 1000)]);
    }
}
