<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TemporaryEmailService;
use Illuminate\Http\JsonResponse;

class TemporaryEmailController extends Controller
{
    public function destroy(string $token, TemporaryEmailService $service): JsonResponse
    {
        $temporaryEmail = $service->findByToken($token);

        if (! $temporaryEmail) {
            return response()->json(['success' => false, 'message' => 'Not found or expired.'], 404);
        }

        $service->delete($temporaryEmail);

        return response()->json(['success' => true]);
    }
}
