<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NegaraController;
use App\Services\TranslationService;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\RiskController;

Route::get('/translate-test', function () {

    $translator = new TranslationService();

    return $translator->translate(
        'Global Supply Chain Risk Intelligence Platform',
        'id'
    );

});

/*
|--------------------------------------------------------------------------
| Ganti Bahasa (Publik — bisa diakses tanpa login)
|--------------------------------------------------------------------------
| Route ini untuk mengganti bahasa aplikasi (Indonesia / English)
*/

Route::get('/language/{locale}', [LanguageController::class, 'switch'])
    ->name('language.switch');

/*
|--------------------------------------------------------------------------
| Halaman Utama (Publik)
|--------------------------------------------------------------------------
| Route ini bisa diakses tanpa login
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Route untuk Semua User yang Sudah Login
|--------------------------------------------------------------------------
| Baik admin maupun user biasa bisa mengakses route ini
*/
require __DIR__.'/auth.php';
Route::middleware(['auth'])->group(function () {

    // Dashboard — controller akan menentukan view admin atau user
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});
Route::middleware(['auth', 'admin'])->group(function () {

    Route::resource('negara', NegaraController::class);
    Route::get('negara/sinkronisasi', [NegaraController::class, 'sinkronisasi'])->name('negara.sinkronisasi');
    

});
Route::get('/translate-test', function () {

    $translator = new TranslationService();

    return $translator->translate(
        'Global Supply Chain Risk Intelligence Platform',
        'id'
    );

});
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('negara', NegaraController::class);

    Route::get('/negara/sync/data', [NegaraController::class, 'sync'])
        ->name('negara.sync');

    Route::get('/weather/{id}/sync', [WeatherController::class, 'sync'])
        ->name('weather.sync');

    Route::get('/currency/{id}/sync', [CurrencyController::class, 'sync'])
        ->name('currency.sync');

    Route::get('/risk/{id}/calculate', [RiskController::class, 'calculate'])
        ->name('risk.calculate');
});
/*
|--------------------------------------------------------------------------
| Route Khusus Admin
|--------------------------------------------------------------------------
| Hanya user dengan peran 'admin' yang bisa mengakses route ini.
| Jika user biasa mencoba akses, akan muncul error 403.
*/

Route::middleware(['auth', 'admin'])->group(function () {

    // CRUD data master (hanya admin)
    // Route::resource('negara', NegaraController::class);
    // Route::resource('cuaca', CuacaController::class);
    // Route::resource('pelabuhan', PelabuhanController::class);
    // Route::resource('berita', BeritaController::class);
    // Route::resource('artikel', ArtikelController::class);
});

// Load route autentikasi (login, register, logout, dll)
require __DIR__.'/auth.php';
