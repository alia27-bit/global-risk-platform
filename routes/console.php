<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Services\MonitoringSyncService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('monitoring:sync {type=all}', function (MonitoringSyncService $sync) {
    $type = $this->argument('type');
    $valid = ['all', 'countries', 'ports', 'weather', 'economy', 'exchange', 'news', 'risk'];
    if (! in_array($type, $valid, true)) {
        $this->error('Type tidak valid: '.implode(', ', $valid));
        return self::FAILURE;
    }

    if (in_array($type, ['all', 'countries'], true)) $this->info('Countries: '.$sync->countries());
    if (in_array($type, ['all', 'ports'], true)) $this->info('Ports: '.$sync->ports());
    if (in_array($type, ['all', 'news'], true)) $this->info('News: '.$sync->news());

    if (! in_array($type, ['countries', 'ports', 'news'], true)) {
        foreach ($sync->monitoredCountries() as $country) {
            if (in_array($type, ['all', 'weather'], true)) $sync->weather($country);
            if (in_array($type, ['all', 'economy'], true)) $sync->economy($country);
            if (in_array($type, ['all', 'exchange'], true)) $sync->exchange($country);
            if (in_array($type, ['all', 'risk'], true)) $sync->risk($country);
            $this->line($country->code.' diperbarui.');
        }
    }

    return self::SUCCESS;
})->purpose('Sinkronisasi otomatis data pemantauan dari API eksternal.');

Schedule::command('monitoring:sync weather')->everyTenMinutes()->withoutOverlapping();
Schedule::command('monitoring:sync risk')->everyTenMinutes()->withoutOverlapping();
Schedule::command('monitoring:sync exchange')->everyFifteenMinutes()->withoutOverlapping();
Schedule::command('monitoring:sync news')->hourly()->withoutOverlapping();
Schedule::command('monitoring:sync economy')->dailyAt('01:00')->withoutOverlapping();
Schedule::command('monitoring:sync countries')->dailyAt('02:00')->withoutOverlapping();
Schedule::command('monitoring:sync ports')->weeklyOn(1, '03:00')->withoutOverlapping();
