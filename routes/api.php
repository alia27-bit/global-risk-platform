<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::prefix('v1')
    ->name('api.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', [ApiController::class, 'dashboard']);

        /*
        |--------------------------------------------------------------------------
        | Countries
        |--------------------------------------------------------------------------
        */

        Route::get('/countries', [ApiController::class, 'countries']);
        Route::get('/countries/{country}', [ApiController::class, 'country']);

        /*
        |--------------------------------------------------------------------------
        | Weather
        |--------------------------------------------------------------------------
        */

        Route::get('/weather', [ApiController::class, 'weather']);
        Route::get('/weather/{country}', [ApiController::class, 'weatherDetail']);

        /*
        |--------------------------------------------------------------------------
        | Economic Indicators
        |--------------------------------------------------------------------------
        */

        Route::get('/economic-indicators', [ApiController::class, 'economicIndicators']);
        Route::get('/economic-indicators/{country}', [ApiController::class, 'economicDetail']);

        /*
        |--------------------------------------------------------------------------
        | Exchange Rates
        |--------------------------------------------------------------------------
        */

        Route::get('/exchange-rates', [ApiController::class, 'exchangeRates']);
        Route::get('/exchange-rates/{country}', [ApiController::class, 'exchangeDetail']);

        /*
        |--------------------------------------------------------------------------
        | News
        |--------------------------------------------------------------------------
        */

        Route::get('/news', [ApiController::class, 'news']);
        Route::get('/news/{country}', [ApiController::class, 'newsDetail']);

        /*
        |--------------------------------------------------------------------------
        | Ports
        |--------------------------------------------------------------------------
        */

        Route::get('/ports', [ApiController::class, 'ports']);
        Route::get('/ports/{country}', [ApiController::class, 'portDetail']);

        /*
        |--------------------------------------------------------------------------
        | Risk Scores
        |--------------------------------------------------------------------------
        */

        Route::get('/risk-scores', [ApiController::class, 'riskScores']);
        Route::get('/risk-scores/{country}', [ApiController::class, 'riskDetail']);

    });