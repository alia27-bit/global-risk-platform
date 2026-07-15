<?php

return [

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'restcountries' => [
        'url' => env('REST_COUNTRIES_URL'),
    ],

    'openmeteo' => [
        'url' => env('OPEN_METEO_URL'),
    ],

    'worldbank' => [
        'url' => env('WORLD_BANK_URL'),
    ],

    'exchange' => [
        'url' => env('EXCHANGE_RATE_URL'),
        'key' => env('EXCHANGE_RATE_API_KEY'),
    ],

    'gnews' => [
        'url' => env('GNEWS_URL'),
        'key' => env('GNEWS_API_KEY'),
    ],

    'google_translate' => [
        'key' => env('GOOGLE_TRANSLATE_API_KEY'),
    ],

];