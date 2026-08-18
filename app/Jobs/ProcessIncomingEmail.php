<?php

namespace App\Jobs;

use App\Services\EmailReceiveService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessIncomingEmail implements ShouldQueue
{
    use Queueable;

    /**
     * @param  array<string, mixed>  $payload
     */
    public function __construct(
        public array $payload
    ) {}

    public function handle(EmailReceiveService $service): void
    {
        try {
            $service->receive($this->payload);
        } catch (\RuntimeException $e) {
            Log::warning('Email rejected', ['reason' => $e->getMessage(), 'to' => $this->payload['to'] ?? 'unknown']);
        }
    }

    public int $tries = 3;

    public int $backoff = 10;
}
