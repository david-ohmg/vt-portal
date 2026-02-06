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
    public function getBatches(?int $userId = null): array
    {
        $params = ($userId && $userId > 0) ? ['female_vt' => $userId] : [];
        return $this->fetchFromEndpoint('batches/batches/', $params);
    }

    /**
     * Fetch AA batches from the API
     */
    public function getAaBatches(?int $userId = null): array
    {
        $params = ($userId && $userId > 0) ? ['voice_talent_id' => $userId] : [];
        return $this->fetchFromEndpoint('aa-tracking/aa-tracking/', $params);
    }

    /**
     * Generic method to fetch from any OHMG endpoint
     */
    private function fetchFromEndpoint(string $endpoint, array $queryParams = []): array
    {
        $filteredParams = array_filter($queryParams, fn($value) => !is_null($value));
        $queryString = !empty($filteredParams) ? '?' . http_build_query($filteredParams) : '';

        $response = Http::withToken($this->token, 'Token')
            ->get($this->baseUrl . $endpoint . $queryString);

        return $response->json() ?? [];
    }
}
