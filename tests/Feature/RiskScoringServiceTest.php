<?php

use App\Services\RiskScoringService;

it('calculates the documented weighted score', function () {
    config()->set('monitoring.weights', ['weather' => 30, 'inflation' => 20, 'currency' => 10, 'news' => 40]);
    expect((new RiskScoringService)->calculate(20, 40, 10, 50))->toBe(35.0);
});

it('uses low medium and high thresholds consistently', function () {
    config()->set('monitoring.risk', ['low' => 30, 'medium' => 60, 'high' => 100]);
    $service = new RiskScoringService;
    expect($service->category(29.99))->toBe('Low')->and($service->category(30))->toBe('Medium')
        ->and($service->category(59.99))->toBe('Medium')->and($service->category(60))->toBe('High');
});

it('scores currency by movement rather than denomination', function () {
    $service = new RiskScoringService;
    expect($service->currencyScore(16000, 16000))->toBe(0.0)->and($service->currencyScore(17600, 16000))->toBe(100.0);
});
