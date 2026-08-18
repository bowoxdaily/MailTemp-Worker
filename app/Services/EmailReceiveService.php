<?php

namespace App\Services;

use App\Models\BlockedDomain;
use App\Models\BlockedSender;
use App\Models\Email;
use App\Models\Setting;
use App\Models\TemporaryEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EmailReceiveService
{
    /**
     * Process incoming email from Cloudflare Worker.
     *
     * @param  array{from: string, to: string, subject: string, body_html?: string, body_text?: string, size?: int, attachments?: array<int, array{filename: string, mime_type: string, size: int, content: string}>}  $payload
     */
    public function receive(array $payload): Email
    {
        $this->validateSender($payload['from']);
        $this->validateSize($payload['size'] ?? 0);

        $temporaryEmail = $this->findRecipient($payload['to']);

        $email = $temporaryEmail->emails()->create([
            'from_address' => $payload['from'],
            'from_name' => $payload['from_name'] ?? null,
            'subject' => $payload['subject'] ?? '(No Subject)',
            'body_html' => $payload['body_html'] ?? null,
            'body_text' => $payload['body_text'] ?? null,
            'size_bytes' => $payload['size'] ?? 0,
            'is_read' => false,
            'received_at' => now(),
        ]);

        if (! empty($payload['attachments'])) {
            $this->processAttachments($email, $payload['attachments']);
        }

        Log::info('Email received', ['email_id' => $email->id, 'to' => $payload['to']]);

        return $email;
    }

    private function validateSender(string $sender): void
    {
        if (BlockedSender::where('email', $sender)->where('status', 'active')->exists()) {
            throw new \RuntimeException('Sender is blocked.');
        }

        $senderDomain = substr($sender, strpos($sender, '@') + 1);
        if (BlockedDomain::where('domain', $senderDomain)->where('status', 'active')->exists()) {
            throw new \RuntimeException('Sender domain is blocked.');
        }
    }

    private function validateSize(int $size): void
    {
        $maxSize = (int) Setting::get('max_email_size_bytes', 10485760);
        if ($size > $maxSize) {
            throw new \RuntimeException('Email exceeds maximum size.');
        }
    }

    private function findRecipient(string $to): TemporaryEmail
    {
        $temporaryEmail = TemporaryEmail::where('email_address', $to)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->first();

        if (! $temporaryEmail) {
            throw new \RuntimeException('Recipient not found or expired.');
        }

        return $temporaryEmail;
    }

    /**
     * @param  array<int, array{filename: string, mime_type: string, size: int, content: string}>  $attachments
     */
    private function processAttachments(Email $email, array $attachments): void
    {
        $maxAttachmentSize = (int) Setting::get('max_attachment_size_bytes', 5242880);

        foreach ($attachments as $attachment) {
            if ($attachment['size'] > $maxAttachmentSize) {
                Log::warning('Attachment too large, skipped', ['filename' => $attachment['filename']]);

                continue;
            }

            $path = 'attachments/'.$email->id.'/'.$attachment['filename'];
            Storage::put($path, base64_decode($attachment['content']));

            $email->attachments()->create([
                'filename' => $attachment['filename'],
                'mime_type' => $attachment['mime_type'],
                'size_bytes' => $attachment['size'],
                'storage_path' => $path,
            ]);
        }
    }
}
