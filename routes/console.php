<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Refresh Instagram token setiap 30 hari (token berlaku 60 hari)
Schedule::command('instagram:refresh-token')
    ->monthlyOn(1, '02:00')
    ->appendOutputTo(storage_path('logs/instagram-token.log'));
