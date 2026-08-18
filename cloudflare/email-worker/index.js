/**
 * Cloudflare Email Worker
 *
 * Receives emails via Cloudflare Email Routing,
 * parses them, and forwards to Laravel backend.
 *
 * Environment variables (set via wrangler secret):
 *   BACKEND_URL - Laravel app URL (e.g. https://tempmail.example.com)
 *   WORKER_SECRET - Must match cloudflare_worker_secret in DB settings
 */

import PostalMime from 'postal-mime';

export default {
  async email(message, env) {
    let stage = 'start';

    try {
      stage = 'read email';
      const rawEmail = await new Response(message.raw).arrayBuffer();

      stage = 'parse email';
      const parser = new PostalMime();
      const parsed = await parser.parse(rawEmail);

      stage = 'encode attachments';
      const attachments = (parsed.attachments || []).map(att => ({
        filename: att.filename || 'unnamed',
        mime_type: att.mimeType || 'application/octet-stream',
        size: att.content.byteLength,
        content: toBase64(att.content),
      }));

      const payload = {
        from: message.from,
        to: message.to,
        from_name: parsed.from?.name || null,
        subject: parsed.subject || '(No Subject)',
        body_html: parsed.html || null,
        body_text: parsed.text || null,
        size: rawEmail.byteLength,
        attachments,
      };

      stage = 'forward to backend';
      const backendUrl = env.BACKEND_URL?.replace(/^\uFEFF/, '').trim().replace(/\/+$/, '');
      const workerSecret = env.WORKER_SECRET?.replace(/^\uFEFF/, '').trim();
      if (!backendUrl || !workerSecret) {
        throw new Error('BACKEND_URL or WORKER_SECRET is not configured');
      }

      const response = await fetch(`${backendUrl}/api/worker/receive`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Worker-Secret': workerSecret,
          'Accept': 'application/json',
        },
        body: JSON.stringify(payload),
      });

      if (!response.ok) {
        const body = await response.text();
        console.error(`Backend error ${response.status}: ${body.slice(0, 1000)}`);
        message.setReject(`Backend rejected: ${response.status}`);
      }
    } catch (error) {
      console.error(`Worker error at ${stage}:`, error instanceof Error ? error.stack : error);
      message.setReject('Processing failed');
    }
  },
};

function toBase64(content) {
  const bytes = new Uint8Array(content);
  let binary = '';

  for (let index = 0; index < bytes.length; index += 0x8000) {
    binary += String.fromCharCode(...bytes.subarray(index, index + 0x8000));
  }

  return btoa(binary);
}
