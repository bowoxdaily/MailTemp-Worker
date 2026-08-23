<?php

use App\Http\Controllers\Api\EmailReceiveController;
use App\Http\Controllers\Api\GenerateController;
use App\Http\Controllers\Api\InboxController;
use App\Http\Controllers\Api\TemporaryEmailController;
use Illuminate\Support\Facades\Route;

// Public API (session-based, rate limited)
Route::middleware(['web', 'throttle:api'])->prefix('v1')->group(function () {
    Route::post('/generate', GenerateController::class)->middleware('throttle:generate')->name('api.generate');
    Route::get('/inbox/{token}', [InboxController::class, 'index'])->name('api.inbox');
    Route::get('/inbox/{token}/messages/{id}', [InboxController::class, 'show'])->name('api.inbox.show');
    Route::get('/inbox/{token}/messages/{id}/attachments/{attachmentId}', [InboxController::class, 'attachment'])->name('api.inbox.attachment');
    Route::delete('/inbox/{token}/messages/{id}', [InboxController::class, 'destroy'])->name('api.inbox.destroy');
    Route::delete('/email/{token}', [TemporaryEmailController::class, 'destroy'])->name('api.email.destroy');
});

// Worker endpoint (secret-based auth, no session)
Route::post('/worker/receive', EmailReceiveController::class)
    ->middleware('throttle:worker')
    ->name('api.worker.receive');
