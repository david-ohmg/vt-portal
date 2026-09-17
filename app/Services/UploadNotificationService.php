<?php

namespace App\Services;

use App\Mail\PortalMail;
use Illuminate\Support\Facades\Http;
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

        Mail::to($vtEmail)->cc($recipientEmail)->send(new PortalMail([
            'subject' => "Files Uploaded for {$customerName} ({$batchId})",
            'message' => $body,
            'attachments' => $filePaths,
        ]));

        $this->notifyBatchRecorded($batchId);
    }

    /**
     * Notify the OnHold Wizard API that a batch has been recorded
     */
    private function notifyBatchRecorded(string $batchId): void
    {
        $numericBatchId = (int) str_replace(['aa-', 's-'], '', $batchId);

        Http::withToken(config('services.ohmg.token'), 'Token')
            ->put(config('services.onholdwizard.url') . "batches/recorded/{$numericBatchId}");
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
