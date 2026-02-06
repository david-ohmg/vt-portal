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
    protected function loadAllBatches(OhmgApiService $apiService): array
    {
        $userId = $this->getUserVtId();

        return [
            'batches' => $apiService->getBatches($userId),
            'aa_batches' => $apiService->getAaBatches($userId),
        ];
    }
}
