<?php

use App\Services\ExpirationService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('emails:cleanup', function () {
    $count = app(ExpirationService::class)->cleanup();
    $this->info("Cleaned up {$count} expired emails.");
})->purpose('Clean up expired temporary emails');

Schedule::command('emails:cleanup')->everyMinute();
