<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule untuk auto-generate alpha
Schedule::command('attendance:generate-alpha')
    ->dailyAt('00:01')
    ->withoutOverlapping()
    ->onOneServer();
