<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessIncomingEmail;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailReceiveController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        // Validate worker secret
        $secret = Setting::get('cloudflare_worker_secret');
        if (! $secret || $request->header('X-Worker-Secret') !== $secret) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'from' => 'required|email',
            'to' => 'required|email',
            'subject' => 'nullable|string|max:998',
            'body_html' => 'nullable|string',
            'body_text' => 'nullable|string',
            'from_name' => 'nullable|string|max:255',
            'size' => 'nullable|integer',
            'attachments' => 'nullable|array',
            'attachments.*.filename' => 'required|string',
            'attachments.*.mime_type' => 'required|string',
            'attachments.*.size' => 'required|integer',
            'attachments.*.content' => 'required|string',
        ]);

        ProcessIncomingEmail::dispatch($request->all());

        return response()->json(['success' => true]);
    }
}
