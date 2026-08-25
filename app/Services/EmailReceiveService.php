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
            'body_html' => $this->sanitizeHtml($payload['body_html'] ?? null),
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
        if (BlockedSender::where('email_address', $sender)->exists()) {
            throw new \RuntimeException('Sender is blocked.');
        }

        $senderDomain = substr($sender, strpos($sender, '@') + 1);
        if (BlockedDomain::where('domain', $senderDomain)->exists()) {
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
            $content = base64_decode($attachment['content'], true);
            $filename = basename($attachment['filename']);
            if (
                $content === false || $filename !== $attachment['filename']
                || strlen($content) !== $attachment['size'] || $attachment['size'] > $maxAttachmentSize
            ) {
                Log::warning('Attachment too large, skipped', ['filename' => $attachment['filename']]);

                continue;
            }

            $path = 'attachments/'.$email->id.'/'.str()->random(32).'-'.$filename;
            Storage::put($path, $content);

            $email->attachments()->create([
                'filename' => $filename,
                'mime_type' => $attachment['mime_type'],
                'size_bytes' => $attachment['size'],
                'storage_path' => $path,
            ]);
        }
    }

    private function sanitizeHtml(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return $html;
        }

        $document = new \DOMDocument;
        libxml_use_internal_errors(true);
        $document->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        // Strip forbidden tags completely (including their inner content)
        $disallowedTags = ['script', 'iframe', 'object', 'embed', 'applet', 'frame', 'frameset', 'form', 'input', 'button', 'textarea', 'select', 'link', 'base'];
        foreach ($disallowedTags as $tag) {
            $elements = iterator_to_array($document->getElementsByTagName($tag));
            foreach ($elements as $el) {
                $el->parentNode?->removeChild($el);
            }
        }

        // Whitelisted HTML tags in rich emails
        $allowedTags = [
            'html', 'head', 'body', 'meta', 'title', 'style',
            'div', 'p', 'span', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'hr', 'br', 'pre', 'code',
            'b', 'strong', 'i', 'em', 'u', 's', 'strike', 'del', 'small', 'sub', 'sup', 'blockquote', 'center', 'font',
            'ul', 'ol', 'li', 'dl', 'dt', 'dd',
            'table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td', 'caption', 'colgroup', 'col',
            'a', 'img',
        ];

        // Safe attributes
        $allowedAttributes = [
            'class', 'id', 'style', 'width', 'height', 'align', 'valign',
            'bgcolor', 'color', 'border', 'cellpadding', 'cellspacing',
            'colspan', 'rowspan', 'alt', 'title', 'target', 'rel', 'src', 'href', 'dir', 'lang',
        ];

        $allElements = iterator_to_array($document->getElementsByTagName('*'));
        foreach ($allElements as $node) {
            $nodeName = strtolower($node->nodeName);
            if (! in_array($nodeName, $allowedTags, true)) {
                $parent = $node->parentNode;
                if ($parent) {
                    while ($node->firstChild) {
                        $parent->insertBefore($node->firstChild, $node);
                    }
                    $parent->removeChild($node);
                }

                continue;
            }

            if ($node->hasAttributes()) {
                foreach (iterator_to_array($node->attributes) as $attribute) {
                    $attrName = strtolower($attribute->name);
                    $attrValue = trim($attribute->value);

                    // Disallow event handlers (on*) or unallowed attributes
                    if (str_starts_with($attrName, 'on') || ! in_array($attrName, $allowedAttributes, true)) {
                        $node->removeAttribute($attribute->name);

                        continue;
                    }

                    // Check href safe protocols and enforce target/rel
                    if ($attrName === 'href') {
                        if (! preg_match('/^(https?:|mailto:|tel:|#|\/)/i', $attrValue)) {
                            $node->removeAttribute($attribute->name);

                            continue;
                        }
                        $node->setAttribute('target', '_blank');
                        $node->setAttribute('rel', 'noopener noreferrer nofollow');
                    }

                    // Check src safe protocols
                    if ($attrName === 'src') {
                        if (! preg_match('/^(https?:|data:image\/|cid:|\/)/i', $attrValue)) {
                            $node->removeAttribute($attribute->name);

                            continue;
                        }
                    }

                    // Sanitize inline style from expression or javascript execution
                    if ($attrName === 'style') {
                        if (preg_match('/(expression|javascript:|behavior|vbscript:|-moz-binding)/i', $attrValue)) {
                            $node->removeAttribute($attribute->name);

                            continue;
                        }
                    }
                }
            }
        }

        $cleanHtml = $document->saveHTML();

        return $cleanHtml ? trim($cleanHtml) : null;
    }
}
