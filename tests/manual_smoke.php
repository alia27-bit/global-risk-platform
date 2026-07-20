<?php

use App\Models\Country;
use App\Models\User;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();
$app->instance('request', Request::create('/'));

$failures = [];

function check(Kernel $kernel, string $label, string $uri, int|array $expected = 200): void
{
    global $failures;
    $request = Request::create($uri, 'GET', [], [], [], ['HTTP_ACCEPT' => str_starts_with($uri, '/api/') ? 'application/json' : 'text/html']);
    $response = $kernel->handle($request);
    $allowed = (array) $expected;
    $ok = in_array($response->getStatusCode(), $allowed, true);
    echo sprintf("%-42s %3d %s\n", $label, $response->getStatusCode(), $ok ? 'OK' : 'FAIL');
    if (! $ok) $failures[] = [$label, $uri, $response->getStatusCode()];
    $kernel->terminate($request, $response);
}

$country = Country::has('weather')->has('economicIndicator')->has('exchangeRate')->has('riskScore')->first()
    ?? Country::whereNotNull('latitude')->whereNotNull('longitude')->first()
    ?? Country::firstOrFail();
$admin = User::where('peran', 'admin')->firstOrFail();
$user = User::where('peran', 'user')->firstOrFail();

Auth::logout();
check($kernel, 'Guest login', '/login');
check($kernel, 'Guest register', '/register');

Auth::login($admin);
foreach ([
    'Admin dashboard' => '/dashboard',
    'Countries index' => '/countries',
    'Country detail' => '/countries/'.$country->id,
    'Country create' => '/countries/create',
    'Weather detail' => '/weather/'.$country->id,
    'Weather global map' => '/weather/map',
    'Economy detail' => '/economic/'.$country->id,
    'Exchange detail' => '/exchange/'.$country->id,
    'Ports index' => '/ports',
    'Ports map' => '/ports/map',
    'Risk index' => '/risk',
    'Risk detail' => '/risk/'.$country->id,
    'News index' => '/news',
    'News create' => '/news/create',
    'Comparison' => '/comparison',
    'Profile' => '/profile',
    'Admin users' => '/admin/users',
    'Admin articles' => '/admin/articles',
] as $label => $uri) check($kernel, $label, $uri);

Auth::login($user);
foreach ([
    'User dashboard' => '/dashboard',
    'User watchlist' => '/watchlist',
    'User countries' => '/countries',
    'User risk' => '/risk',
    'User news' => '/news',
    'User comparison' => '/comparison',
    'User profile' => '/profile',
] as $label => $uri) check($kernel, $label, $uri);
check($kernel, 'User blocked admin users', '/admin/users', 403);
check($kernel, 'User blocked country create', '/countries/create', 403);
check($kernel, 'User blocked news create', '/news/create', 403);
check($kernel, 'User blocked country sync', '/countries/sync', 403);
check($kernel, 'User blocked weather sync', '/weather/'.$country->id.'/sync', 403);
check($kernel, 'User blocked economy sync', '/economic/'.$country->id.'/sync', 403);
check($kernel, 'User blocked exchange sync', '/exchange/'.$country->id.'/sync', 403);
check($kernel, 'User blocked global news sync', '/news/sync/global', 403);
check($kernel, 'User blocked country news sync', '/news/'.$country->id.'/sync', 403);
check($kernel, 'User blocked risk calculation', '/risk/'.$country->id.'/calculate', 403);
check($kernel, 'User blocked port sync', '/ports/sync', 403);

Auth::login($admin);
check($kernel, 'Admin watchlist access', '/watchlist');

Auth::logout();
foreach ([
    'API countries' => '/api/countries',
    'API risk' => '/api/risk',
    'API ports' => '/api/ports',
    'API news' => '/api/news',
    'API currency' => '/api/currency',
    'API v1 dashboard' => '/api/v1/dashboard',
    'API v1 live AJAX' => '/api/v1/live',
    'API v1 country detail' => '/api/v1/countries/'.$country->id,
    'API v1 weather detail' => '/api/v1/weather/'.$country->id,
    'API v1 economy detail' => '/api/v1/economic-indicators/'.$country->id,
    'API v1 exchange detail' => '/api/v1/exchange-rates/'.$country->id,
    'API v1 risk detail' => '/api/v1/risk-scores/'.$country->id,
] as $label => $uri) check($kernel, $label, $uri);

if ($failures) {
    fwrite(STDERR, json_encode($failures, JSON_PRETTY_PRINT).PHP_EOL);
    exit(1);
}

echo "ALL_SMOKE_TESTS_PASSED\n";
