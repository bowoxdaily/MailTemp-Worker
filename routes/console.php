<?php

use App\Models\Setting;
use App\Services\ExpirationService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('emails:cleanup', function () {
    $count = app(ExpirationService::class)->cleanup();
    Cache::put('scheduler:last_run', now()->toIso8601String());
    $this->info("Cleaned up {$count} expired emails.");
})->purpose('Clean up expired temporary emails');

try {
    $interval = (int) Setting::get('cleanup_interval_minutes', 1);
} catch (Throwable) {
    $interval = 1;
}
$interval = max(1, $interval);

Schedule::command('emails:cleanup')->cron("*/{$interval} * * * *");
