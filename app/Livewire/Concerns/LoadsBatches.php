<?php

namespace App\Livewire\Concerns;

use App\Models\User;
use App\Services\OhmgApiService;

trait LoadsBatches
{
    /**
     * Get the current user's VT ID
     */
    protected function getUserVtId(): ?int
    {
        return User::where('email', auth()->user()->getEmailForVerification())
            ->value('vt_id');
    }

    /**
     * Load both batch types
     */
    protected function loadAllBatches(OhmgApiService $apiService, ?bool $archive = false): array
    {
        $userId = $this->getUserVtId();

        return [
            'batches' => $apiService->getBatches($userId, $archive),
            'aa_batches' => $apiService->getAaBatches($userId, $archive),
        ];
    }
}
