<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TemporaryEmailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GenerateController extends Controller
{
    public function __invoke(Request $request, TemporaryEmailService $service): JsonResponse
    {
        $request->validate([
            'expiry_minutes' => 'sometimes|integer|in:10,30,60',
            'username' => 'sometimes|string|min:3|max:30|regex:/^[a-z0-9\._-]+$/i',
            'domain' => [
                'sometimes',
                'string',
                Rule::exists('domains', 'domain')->where('is_active', 1),
            ],
        ]);

        try {
            $result = $service->generate(
                $request->session()->getId(),
                $request->only(['expiry_minutes', 'username', 'domain'])
            );

            return response()->json([
                'success' => true,
                ...$result,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
