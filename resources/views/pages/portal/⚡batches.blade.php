<?php

use App\Livewire\Concerns\LoadsBatches;
use App\Services\OhmgApiService;
use App\Traits\HasMultipleToggles;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('My Batches')]
class extends Component {
    use HasMultipleToggles, LoadsBatches;

    public ?int $openId = null;
    public ?int $aaId = null;

    public function render(OhmgApiService $apiService)
    {
        return view('pages.portal.⚡batches', $this->loadAllBatches($apiService));
    }
};
?>

<div class="flex flex-col">
    <h1 class="text-2xl font-bold mt-4 mb-4 text-center">My Batches</h1>

    <div class="flex flex-col md:flex-row justify-center gap-4">
        <x-batch-list
            title="Script Batches ({{count($batches)}})"
            :batches="$batches"
            type="script"
            :openId="$openId"
            toggleMethod="toggle" />

        <x-batch-list
            title="AA Batches ({{count($aa_batches)}})"
            :batches="$aa_batches"
            type="aa"
            :openId="$aaId"
            toggleMethod="aa_toggle" />
    </div>
</div>
