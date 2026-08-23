<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessIncomingEmail;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EmailReceiveController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $secret = Setting::get('cloudflare_worker_secret');
        $timestamp = $request->header('X-Worker-Timestamp');
        $signature = $request->header('X-Worker-Signature');
        $body = $request->getContent();
        $expected = $secret && $timestamp
            ? hash_hmac('sha256', $timestamp.'.'.$body, $secret)
            : null;

        if (
            ! $secret || ! ctype_digit((string) $timestamp) || abs(time() - (int) $timestamp) > 300
            || ! $signature || ! $expected || ! hash_equals($expected, $signature)
            || ! Cache::add('worker-request:'.$signature, true, now()->addMinutes(5))
        ) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'from' => 'required|email',
            'to' => 'required|email',
            'subject' => 'nullable|string|max:998',
            'body_html' => 'nullable|string|max:500000',
            'body_text' => 'nullable|string|max:500000',
            'from_name' => 'nullable|string|max:255',
            'size' => 'nullable|integer|min:0|max:52428800',
            'attachments' => 'nullable|array|max:10',
            'attachments.*.filename' => ['required', 'string', 'max:255', 'regex:/^[^\\\/]+$/'],
            'attachments.*.mime_type' => 'required|string|max:127',
            'attachments.*.size' => 'required|integer|min:0|max:5242880',
            'attachments.*.content' => 'required|string|max:6990508',
        ]);

        ProcessIncomingEmail::dispatch($request->all());

        return response()->json(['success' => true]);
    }
}
