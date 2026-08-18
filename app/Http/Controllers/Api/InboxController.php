<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TemporaryEmailService;
use Illuminate\Http\JsonResponse;

class InboxController extends Controller
{
    public function __construct(
        private TemporaryEmailService $service
    ) {}

    public function index(string $token): JsonResponse
    {
        $temporaryEmail = $this->service->findByToken($token);

        if (! $temporaryEmail) {
            return response()->json(['success' => false, 'message' => 'Not found or expired.'], 404);
        }

        $emails = $temporaryEmail->emails()
            ->select('id', 'from_address', 'from_name', 'subject', 'is_read', 'received_at')
            ->latest('received_at')
            ->get();

        return response()->json([
            'success' => true,
            'email' => $temporaryEmail->email_address,
            'expires_at' => $temporaryEmail->expires_at->toIso8601String(),
            'messages' => $emails,
        ]);
    }

    public function show(string $token, int $id): JsonResponse
    {
        $temporaryEmail = $this->service->findByToken($token);

        if (! $temporaryEmail) {
            return response()->json(['success' => false, 'message' => 'Not found or expired.'], 404);
        }

        $email = $temporaryEmail->emails()->with('attachments:id,email_id,filename,mime_type,size_bytes')->find($id);

        if (! $email) {
            return response()->json(['success' => false, 'message' => 'Email not found.'], 404);
        }

        $email->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => $email,
        ]);
    }

    public function destroy(string $token, int $id): JsonResponse
    {
        $temporaryEmail = $this->service->findByToken($token);

        if (! $temporaryEmail) {
            return response()->json(['success' => false, 'message' => 'Not found or expired.'], 404);
        }

        $email = $temporaryEmail->emails()->find($id);

        if (! $email) {
            return response()->json(['success' => false, 'message' => 'Email not found.'], 404);
        }

        $email->attachments()->delete();
        $email->delete();

        return response()->json(['success' => true]);
    }
}
