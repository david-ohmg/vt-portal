<?php

namespace App\Services;

use App\Models\MyFiles;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class FileUploadService
{
    /**
     * Upload a file and create database record
     */
    public function uploadFile(UploadedFile $file, string $batchId, int $userId): string
    {
        $originalName = $file->getClientOriginalName();
        $batchRoute = $this->parseBatchId($batchId);

        Log::info('Uploading file', [
            'original' => $originalName,
            'size' => $file->getSize(),
            'mime' => $file->getClientMimeType(),
        ]);

        try {
            $subPath = $this->getStoragePath($batchRoute);
            $path = $file->storeAs($subPath, $originalName, 's3');

            $this->verifyFileExists($path);

            $this->createFileRecord($file, $path, $batchId, $userId);

            return $path;

        } catch (\Throwable $e) {
            Log::error('Upload failed', [
                'message' => $e->getMessage(),
                'file' => $originalName,
            ]);
            throw $e;
        }
    }

    /**
     * Parse batch ID into type and number
     */
    public function parseBatchId(string $batchId): array
    {
        $batchNumber = (int) str_replace(['aa-', 's-'], '', $batchId);
        $type = str_contains($batchId, 's-') ? 's' : 'aa';

        return ['type' => $type, 'batch_id' => $batchNumber];
    }

    /**
     * Get storage path based on batch type
     */
    private function getStoragePath(array $batchRoute): string
    {
        return $batchRoute['type'] === 's'
            ? 'voice-files'
            : 'aa-files/' . $batchRoute['batch_id'];
    }

    /**
     * Verify file exists in storage
     */
    private function verifyFileExists(string $path): void
    {
        $exists = Storage::disk('s3')->exists($path);

        Log::info('File store result', [
            'path' => $path,
            'exists' => $exists,
            'bucket' => config('filesystems.disks.s3.bucket'),
            'region' => config('filesystems.disks.s3.region'),
        ]);

        if (!$exists) {
            throw new RuntimeException("File upload returned path but object not found: {$path}");
        }
    }

    /**
     * Create database record for uploaded file
     */
    private function createFileRecord(UploadedFile $file, string $path, string $batchId, int $userId): void
    {
        MyFiles::create([
            'name' => $file->getClientOriginalName(),
            'user_id' => $userId,
            'size' => $file->getSize(),
            'path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'batch_id' => $batchId,
        ]);
    }
}
