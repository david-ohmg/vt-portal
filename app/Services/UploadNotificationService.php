<?php

namespace App\Services;

use App\Mail\PortalMail;
use Illuminate\Support\Facades\Mail;

class UploadNotificationService
{
    /**
     * Send upload notification email
     */
    public function sendUploadNotification(
        string $recipientEmail,
        string $customerName,
        string $batchId,
        array $filePaths
    ): void
    {
        $body = $this->buildEmailBody($filePaths);

        Mail::to($recipientEmail)->send(new PortalMail([
            'subject' => "Files Uploaded for {$customerName} ({$batchId})",
            'message' => $body,
        ]));
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
