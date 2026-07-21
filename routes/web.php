<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ComparisonController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EconomicIndicatorController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiskController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\WeatherController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/language/{locale}', [LanguageController::class, 'switch'])
    ->name('language.switch');

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class,'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::controller(ProfileController::class)->group(function () {

        Route::get('/profile','edit')->name('profile.edit');

        Route::patch('/profile','update')->name('profile.update');

        Route::delete('/profile','destroy')->name('profile.destroy');

    });

    /*
    |--------------------------------------------------------------------------
    | Countries
    |--------------------------------------------------------------------------
    */

    Route::get('/countries/sync',
        [CountryController::class,'sync'])
        ->middleware('admin')->name('countries.sync');

    Route::resource('countries', CountryController::class)->except(['index', 'show'])->middleware('admin');
    Route::resource('countries', CountryController::class)->only(['index', 'show']);

    /*
    |--------------------------------------------------------------------------
    | Weather
    |--------------------------------------------------------------------------
    */

    Route::get('/weather/map',
        [WeatherController::class,'map'])
        ->name('weather.map');

    Route::get('/weather/{country}',
        [WeatherController::class,'show'])
        ->name('weather.show');

    Route::get('/weather/{country}/sync',
        [WeatherController::class,'sync'])
        ->middleware('admin')->name('weather.sync');

    /*
    |--------------------------------------------------------------------------
    | Economy
    |--------------------------------------------------------------------------
    */

    Route::get('/economic',
        [EconomicIndicatorController::class,'index'])
        ->name('economy.index');

    Route::get('/economic/{country}',
        [EconomicIndicatorController::class,'show'])
        ->name('economy.show');

    Route::get('/economic/{country}/sync',
        [EconomicIndicatorController::class,'sync'])
        ->middleware('admin')->name('economy.sync');

    /*
    |--------------------------------------------------------------------------
    | Exchange Rate
    |--------------------------------------------------------------------------
    */

    Route::get('/exchange',
        [ExchangeRateController::class,'index'])
        ->name('exchange.index');

    Route::get('/exchange/{country}',
        [ExchangeRateController::class,'show'])
        ->name('exchange.show');

    Route::get('/exchange/{country}/sync',
        [ExchangeRateController::class,'sync'])
        ->middleware('admin')->name('exchange.sync');

    /*
    |--------------------------------------------------------------------------
    | News
    |--------------------------------------------------------------------------
    */

    Route::get('/news/sync/global', [NewsController::class, 'syncGlobal'])
        ->middleware('admin')->name('news.sync-global');

    Route::get('/news/{country}/sync',
        [NewsController::class,'sync'])
        ->middleware('admin')->name('news.sync');

    Route::resource('news', NewsController::class)->except(['index', 'show'])->middleware('admin');
    Route::resource('news', NewsController::class)->only(['index', 'show']);

    Route::get('/articles', [ArticleController::class, 'publicIndex'])->name('articles.public.index');
    Route::get('/articles/{article:slug}', [ArticleController::class, 'publicShow'])->name('articles.public.show');

    /*
    |--------------------------------------------------------------------------
    | Ports
    |--------------------------------------------------------------------------
    */

    Route::get('/ports/map',
        [PortController::class,'map'])
        ->name('ports.map');

    Route::get('/ports/search',
        [PortController::class,'search'])
        ->name('ports.search');

    Route::get('/ports/sync', [PortController::class, 'sync'])
        ->middleware('admin')->name('ports.sync');

    Route::resource('ports', PortController::class)->except(['index', 'show'])->middleware('admin');
    Route::resource('ports', PortController::class)->only(['index', 'show']);

    /*
    |--------------------------------------------------------------------------
    | Risk Score
    |--------------------------------------------------------------------------
    */

    Route::get('/risk',
        [RiskController::class,'index'])
        ->name('risk.index');

    Route::get('/risk/{country}',
        [RiskController::class,'show'])
        ->name('risk.show');

    Route::get('/risk/{country}/calculate',
        [RiskController::class,'calculate'])
        ->middleware('admin')->name('risk.calculate');

    /*
    |--------------------------------------------------------------------------
    | Favorite Monitoring (Watchlist)
    |--------------------------------------------------------------------------
    */

    Route::middleware('user')->group(function () {
        Route::get('/favorite-monitoring', [WatchlistController::class, 'index'])->name('favorite-monitoring.index');
        Route::post('/favorite-monitoring/{country}', [WatchlistController::class, 'store'])->name('favorite-monitoring.store');
        Route::delete('/favorite-monitoring/{watchlist}', [WatchlistController::class, 'destroy'])->name('favorite-monitoring.destroy');

        Route::get('/watchlist', fn () => redirect()->route('favorite-monitoring.index'))->name('watchlist.index');
        Route::post('/watchlist/{country}', [WatchlistController::class, 'store'])->name('watchlist.store');
        Route::delete('/watchlist/{watchlist}', [WatchlistController::class, 'destroy'])->name('watchlist.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Comparison
    |--------------------------------------------------------------------------
    */

    Route::get('/comparison',
        [ComparisonController::class,'index'])
        ->name('comparison.index');

    Route::post('/comparison',
        [ComparisonController::class,'compare'])
        ->name('comparison.compare');
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/',
            [AdminController::class,'dashboard'])
            ->name('dashboard');

        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::patch('/users/{user}/role', [AdminController::class, 'updateRole'])->name('users.role');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        Route::resource('articles', ArticleController::class);

    });
