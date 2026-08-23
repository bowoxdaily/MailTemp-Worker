<?php

use App\Jobs\ProcessIncomingEmail;
use App\Models\Attachment;
use App\Models\BlockedDomain;
use App\Models\BlockedSender;
use App\Models\Domain;
use App\Models\Setting;
use App\Models\TemporaryEmail;
use App\Models\User;
use App\Services\EmailReceiveService;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    User::factory()->create(['is_admin' => true]);
    Setting::set('cloudflare_worker_secret', 'test-secret-worker-key');
});

function signPayload(string $body, string $secret, ?int $timestamp = null): array
{
    $timestamp = $timestamp ?? time();
    $signature = hash_hmac('sha256', $timestamp.'.'.$body, $secret);

    return [
        'X-Worker-Timestamp' => (string) $timestamp,
        'X-Worker-Signature' => $signature,
    ];
}

test('it receives email via worker endpoint and dispatches ProcessIncomingEmail job', function () {
    Queue::fake();

    $domain = Domain::create(['domain' => 'testmail.com', 'is_active' => true]);
    TemporaryEmail::create([
        'domain_id' => $domain->id,
        'email_address' => 'user@testmail.com',
        'token' => 'abc123token',
        'session_id' => 'session-123',
        'expires_at' => now()->addMinutes(10),
        'status' => 'active',
    ]);

    $payload = [
        'from' => 'sender@example.com',
        'from_name' => 'John Doe',
        'to' => 'user@testmail.com',
        'subject' => 'Hello World OTP: 123456',
        'body_html' => '<p>Hello <b>World</b></p>',
        'body_text' => 'Hello World',
        'size' => 1024,
    ];

    $body = json_encode($payload);
    $headers = signPayload($body, 'test-secret-worker-key');

    $response = $this->withHeaders($headers)
        ->postJson('/api/worker/receive', $payload);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    Queue::assertPushed(ProcessIncomingEmail::class);
});

test('worker endpoint rejects invalid signature or timestamp', function () {
    $payload = [
        'from' => 'sender@example.com',
        'to' => 'user@testmail.com',
        'subject' => 'Test',
    ];

    // Invalid secret/signature
    $response = $this->withHeaders([
        'X-Worker-Timestamp' => (string) time(),
        'X-Worker-Signature' => 'invalid-signature',
    ])->postJson('/api/worker/receive', $payload);

    $response->assertStatus(401);
});

test('EmailReceiveService parses email and attachments, allowing download via inbox API', function () {
    Storage::fake('local');

    $domain = Domain::create(['domain' => 'testmail.com', 'is_active' => true]);
    $tempEmail = TemporaryEmail::create([
        'domain_id' => $domain->id,
        'email_address' => 'user@testmail.com',
        'token' => 'download-token',
        'session_id' => 'session-download',
        'expires_at' => now()->addMinutes(10),
        'status' => 'active',
    ]);

    $content = 'Sample attachment content';
    $payload = [
        'from' => 'sender@example.com',
        'from_name' => 'Support',
        'to' => 'user@testmail.com',
        'subject' => 'With Attachment',
        'body_text' => 'See attached',
        'size' => 500,
        'attachments' => [
            [
                'filename' => 'test.txt',
                'mime_type' => 'text/plain',
                'size' => strlen($content),
                'content' => base64_encode($content),
            ],
        ],
    ];

    $service = app(EmailReceiveService::class);
    $email = $service->receive($payload);

    expect($email)->not->toBeNull();
    expect($email->temporary_email_id)->toBe($tempEmail->id);

    $attachment = Attachment::where('email_id', $email->id)->first();
    expect($attachment)->not->toBeNull();
    expect($attachment->filename)->toBe('test.txt');

    // Download attachment via API
    $downloadResponse = $this->get("/api/v1/inbox/download-token/messages/{$email->id}/attachments/{$attachment->id}");
    $downloadResponse->assertStatus(200);
});

test('EmailReceiveService rejects blocked senders or blocked sender domains', function () {
    $domain = Domain::create(['domain' => 'testmail.com', 'is_active' => true]);
    TemporaryEmail::create([
        'domain_id' => $domain->id,
        'email_address' => 'user@testmail.com',
        'token' => 'token123',
        'session_id' => 'session-blocked',
        'expires_at' => now()->addMinutes(10),
        'status' => 'active',
    ]);

    BlockedSender::create(['email_address' => 'spammer@spam.com', 'is_active' => true]);
    BlockedDomain::create(['domain' => 'badsite.com', 'is_active' => true]);

    $service = app(EmailReceiveService::class);

    // Blocked sender
    expect(fn () => $service->receive([
        'from' => 'spammer@spam.com',
        'to' => 'user@testmail.com',
        'subject' => 'Spam',
    ]))->toThrow(RuntimeException::class, 'Sender is blocked.');

    // Blocked domain
    expect(fn () => $service->receive([
        'from' => 'anyone@badsite.com',
        'to' => 'user@testmail.com',
        'subject' => 'Spam 2',
    ]))->toThrow(RuntimeException::class, 'Sender domain is blocked.');
});
