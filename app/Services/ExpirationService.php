<?php

namespace App\Services;

use App\Models\TemporaryEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ExpirationService
{
    /**
     * Clean up all expired temporary emails.
     */
    public function cleanup(): int
    {
        $expired = TemporaryEmail::where('status', 'active')
            ->where('expires_at', '<=', now())
            ->get();

        $count = 0;

        foreach ($expired as $temporaryEmail) {
            $this->deleteTemporaryEmail($temporaryEmail);
            $count++;
        }

        if ($count > 0) {
            Log::info('Expired emails cleaned up', ['count' => $count]);
        }

        return $count;
    }

    private function deleteTemporaryEmail(TemporaryEmail $temporaryEmail): void
    {
        // Delete attachment files
        foreach ($temporaryEmail->emails as $email) {
            foreach ($email->attachments as $attachment) {
                Storage::delete($attachment->storage_path);
            }
        }

        // Cascade delete handles DB records (foreign key constraints)
        $temporaryEmail->delete();
    }
}
