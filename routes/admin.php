<?php

use App\Http\Controllers\Admin\AbuseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DomainController;
use App\Http\Controllers\Admin\EmailMonitorController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Auth\AdminLoginController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    // Auth
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminLoginController::class, 'login']);
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

    // Protected
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', DashboardController::class)->name('admin.dashboard');

        Route::resource('domains', DomainController::class)->except('show')->names('admin.domains');
        Route::patch('domains/{domain}/toggle', [DomainController::class, 'toggle'])->name('admin.domains.toggle');

        Route::get('emails', [EmailMonitorController::class, 'index'])->name('admin.emails.index');
        Route::get('emails/{email}', [EmailMonitorController::class, 'show'])->name('admin.emails.show');

        Route::get('abuse', [AbuseController::class, 'index'])->name('admin.abuse.index');
        Route::post('abuse/block-sender', [AbuseController::class, 'blockSender'])->name('admin.abuse.block-sender');
        Route::delete('abuse/unblock-sender/{blockedSender}', [AbuseController::class, 'unblockSender'])->name('admin.abuse.unblock-sender');
        Route::post('abuse/block-domain', [AbuseController::class, 'blockDomain'])->name('admin.abuse.block-domain');
        Route::delete('abuse/unblock-domain/{blockedDomain}', [AbuseController::class, 'unblockDomain'])->name('admin.abuse.unblock-domain');

        Route::get('settings', [SettingController::class, 'index'])->name('admin.settings.index');
        Route::put('settings', [SettingController::class, 'update'])->name('admin.settings.update');
        Route::post('settings/cloudflare-deploy', [SettingController::class, 'deployCloudflare'])->name('admin.settings.cloudflare-deploy');
    });
});
