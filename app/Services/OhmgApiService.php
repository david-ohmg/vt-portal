<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OhmgApiService
{
    private string $baseUrl;
    private string $token;

    public function __construct()
    {
        $this->baseUrl = config('services.ohmg.url');
        $this->token = config('services.ohmg.token');
    }

    /**
     * Fetch batches from the API
     */
    public function getBatches(?int $userId = null, ?bool $archive = false): array
    {
        $path = $archive ? 'batches/archive/' : 'batches/batches/';

        if (!$userId || $userId <= 0) {
            return $this->fetchFromEndpoint($path);
        }

        // The API ANDs female_vt/male_vt filters, so query each role separately and merge
        $batches = collect($this->fetchFromEndpoint($path, ['female_vt' => $userId]))
            ->merge($this->fetchFromEndpoint($path, ['male_vt' => $userId]))
            ->unique('id')
            ->sortByDesc('date_entered');

        // Archive endpoint caps results at 30 per call
        if ($archive) {
            $batches = $batches->take(30);
        }

        return $batches->values()->all();
    }

    /**
     * Fetch AA batches from the API
     */
    public function getAaBatches(?int $userId = null, ?bool $archive = false): array
    {
        $params = ($userId && $userId > 0) ? ['voice_talent_id' => $userId] : [];
        $path = $archive ? 'aa-tracking/archive/' : 'aa-tracking/aa-tracking/';
        return $this->fetchFromEndpoint($path, $params);
    }

    /**
     * Get batch detail by ID
     */
    public function getBatchDetail(string $type, int $batchId): array
    {
        $endpoint = $type === 's'
            ? "batches/batches/{$batchId}"
            : "aa-tracking/aa-tracking/{$batchId}";

        return $this->fetchFromEndpoint($endpoint);
    }

    /**
     * Update batch data
     */
    public function updateBatch(string $type, int $batchId, array $data): array
    {
        $endpoint = ($type === 'aa' ? 'aa-tracking/aa-tracking' : 'batches/batches') . "/{$batchId}/";

        $response = Http::withToken($this->token, 'Token')
            ->put($this->baseUrl . $endpoint, $data);

        return $response->json() ?? [];
    }

    /**
     * Generic method to fetch from any OHMG endpoint
     */
    private function fetchFromEndpoint(string $endpoint, array $queryParams = []): array
    {
        $queryString = !empty($queryParams) ? '?' . http_build_query($queryParams) : '';

        $response = Http::withToken($this->token, 'Token')
            ->get($this->baseUrl . $endpoint . $queryString);

        return $response->json() ?? [];
    }
}
