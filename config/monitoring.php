<?php

return [
    'weights' => [
        'weather' => (float) env('WEATHER_WEIGHT', 30),
        'inflation' => (float) env('INFLATION_WEIGHT', 20),
        'currency' => (float) env('CURRENCY_WEIGHT', 10),
        'news' => (float) env('NEWS_WEIGHT', 40),
    ],
    'risk' => [
        'low' => (float) env('LOW_RISK', 30),
        'medium' => (float) env('MEDIUM_RISK', 60),
        'high' => (float) env('HIGH_RISK', 100),
    ],
    'chart_days' => (int) env('CHART_DAYS', 30),
];
