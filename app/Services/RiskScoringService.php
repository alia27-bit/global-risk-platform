<?php

namespace App\Services;

class RiskScoringService
{
    public function calculate(float $weather, float $inflation, float $currency, float $news): float
    {
        $components = array_map(fn (float $score) => $this->clamp($score), compact('weather', 'inflation', 'currency', 'news'));
        $weights = config('monitoring.weights');
        $sum = max(1, array_sum($weights));

        return round(array_sum(array_map(
            fn (string $key) => $components[$key] * ($weights[$key] / $sum),
            array_keys($components)
        )), 2);
    }

    public function weatherScore(?float $windSpeed, ?float $rainfall, ?float $stormRisk): float
    {
        $wind = $this->clamp(($windSpeed ?? 0) / 75 * 100);
        $rain = $this->clamp(($rainfall ?? 0) / 50 * 100);
        return round(($wind * .50) + ($rain * .20) + ($this->clamp($stormRisk ?? 0) * .30), 2);
    }

    public function inflationScore(?float $inflation): float
    {
        return round($this->clamp(abs($inflation ?? 0) / 20 * 100), 2);
    }

    public function currencyScore(?float $latest, ?float $previous): float
    {
        if ($latest === null || $previous === null || $previous == 0.0) return 0.0;
        return round($this->clamp(abs(($latest - $previous) / $previous) * 1000), 2);
    }

    public function newsScore(iterable $labels): float
    {
        $scores = [];
        foreach ($labels as $label) $scores[] = match ($label) { 'Negative' => 100, 'Positive' => 0, default => 50 };
        return $scores === [] ? 50.0 : round(array_sum($scores) / count($scores), 2);
    }

    public function category(float $score): string
    {
        if ($score < config('monitoring.risk.low', 30)) return 'Low';
        if ($score < config('monitoring.risk.medium', 60)) return 'Medium';
        return 'High';
    }

    private function clamp(float $value): float
    {
        return min(100, max(0, $value));
    }
}
