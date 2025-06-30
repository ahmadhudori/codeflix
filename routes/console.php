<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// for production
// Schedule::command('membership:check')
// 	->daily()
// 	->at('00:00')
// 	->timeZone('Asia/Jakarta')
// 	->withoutOverlapping()
// 	->onOneServer()
// 	->evenInMaintenanceMode();

// for development
Schedule::command('membership:check')->everyMinute();