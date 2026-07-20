<?php

use App\Services\SentimentService;

it('classifies positive neutral and negative text with the lexicon', function () {
    $service = app(SentimentService::class);
    expect($service->analyze('Exports improve with strong growth')['label'])->toBe('Positive')
        ->and($service->analyze('The ministry published a report today')['label'])->toBe('Neutral')
        ->and($service->analyze('Inflation increases while exports decrease due to war')['label'])->toBe('Negative');
});
