<?php

namespace App\Services;

use App\Models\Domain;
use App\Models\Setting;
use App\Models\TemporaryEmail;
use Illuminate\Support\Str;

class TemporaryEmailService
{
    /**
     * Generate a new temporary email address.
     *
     * @param  array{expiry_minutes?: int}  $options
     * @return array{email: string, token: string, expires_at: string}
     */
    public function generate(string $sessionId, array $options = []): array
    {
        $domainName = $options['domain'] ?? null;
        if ($domainName) {
            $domain = Domain::where('domain', $domainName)->where('is_active', true)->first();
            if (! $domain) {
                throw new \RuntimeException('Domain not found or inactive.');
            }
        } else {
            $domain = Domain::where('is_active', true)->inRandomOrder()->first();
            if (! $domain) {
                throw new \RuntimeException('No active domain available.');
            }
        }

        $customUsername = $options['username'] ?? null;
        if ($customUsername) {
            $username = Str::lower($customUsername);
            $emailAddress = $username.'@'.$domain->domain;

            // Check if already exists and active
            $existing = TemporaryEmail::where('email_address', $emailAddress)
                ->where('status', 'active')
                ->where('expires_at', '>', now())
                ->first();

            if ($existing) {
                if ($existing->session_id === $sessionId) {
                    return [
                        'email' => $existing->email_address,
                        'token' => $existing->token,
                        'expires_at' => $existing->expires_at->toIso8601String(),
                    ];
                }
                throw new \RuntimeException('Email address is already in use.');
            }
        } else {
            $username = $this->generateUniqueUsername($domain->id);
        }

        $token = Str::random(64);
        $expiryMinutes = $options['expiry_minutes'] ?? (int) Setting::get('default_expiry_minutes', 10);

        $temporaryEmail = TemporaryEmail::create([
            'email_address' => $username.'@'.$domain->domain,
            'domain_id' => $domain->id,
            'session_id' => $sessionId,
            'token' => $token,
            'status' => 'active',
            'expires_at' => now()->addMinutes($expiryMinutes),
        ]);

        return [
            'email' => $temporaryEmail->email_address,
            'token' => $temporaryEmail->token,
            'expires_at' => $temporaryEmail->expires_at->toIso8601String(),
        ];
    }

    /**
     * Find active temporary email by token.
     */
    public function findByToken(string $token): ?TemporaryEmail
    {
        return TemporaryEmail::where('token', $token)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->first();
    }

    /**
     * Delete temporary email and all associated data.
     */
    public function delete(TemporaryEmail $temporaryEmail): void
    {
        $temporaryEmail->emails()->each(function ($email) {
            $email->attachments()->delete();
        });
        $temporaryEmail->emails()->delete();
        $temporaryEmail->delete();
    }

    private function generateUniqueUsername(int $domainId): string
    {
        do {
            $username = Str::lower(Str::random(8));
        } while (
            TemporaryEmail::where('domain_id', $domainId)
                ->where('email_address', 'like', $username.'@%')
                ->exists()
        );

        return $username;
    }
}
