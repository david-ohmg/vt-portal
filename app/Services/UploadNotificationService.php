<?php

namespace App\Services;

use App\Mail\PortalMail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UploadNotificationService
{
    /**
     * Send upload notification email
     */
    public function sendUploadNotification(
        string $recipientEmail,
        string $vtEmail,
        string $customerName,
        string $batchId,
        array $filePaths
    ): void
    {
        $body = $this->buildEmailBody($filePaths);

        // A mail failure shouldn't stop the recorded writeback below
        try {
            Mail::to($vtEmail)->cc($recipientEmail)->send(new PortalMail([
                'subject' => "Files Uploaded for {$customerName} ({$batchId})",
                'message' => $body,
                'attachments' => $filePaths,
            ]));
        } catch (\Throwable $e) {
            Log::error('Failed to send upload notification email', [
                'batch_id' => $batchId,
                'error' => $e->getMessage(),
            ]);
        }

        $this->notifyBatchRecorded($batchId);
    }

    /**
     * Notify the OnHold Wizard API that a batch has been recorded
     */
    private function notifyBatchRecorded(string $batchId): void
    {
        $numericBatchId = (int) str_replace(['aa-', 's-'], '', $batchId);
        $path = str_contains($batchId, 'aa-') ? 'aa-tracking/recorded' : 'batches/recorded';

        $response = Http::withToken(config('services.ohmg.token'), 'Token')
            ->put(config('services.ohmg.url') . "{$path}/{$numericBatchId}/");

        if ($response->failed()) {
            Log::error('Failed to notify OnHold Wizard that batch was recorded', [
                'batch_id' => $batchId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }
    }

    /**
     * Build HTML email body from file paths
     */
    private function buildEmailBody(array $filePaths): string
    {
        return collect($filePaths)
            ->map(fn($path) => "<p>{$path}</p>")
            ->implode('');
    }
}
