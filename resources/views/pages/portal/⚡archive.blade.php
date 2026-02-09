<?php

use App\Livewire\Concerns\LoadsBatches;
use App\Services\OhmgApiService;
use App\Traits\HasMultipleToggles;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Archive Batches')]
class extends Component
{
    use HasMultipleToggles, LoadsBatches;

    public ?int $openId = null;
    public ?int $aaId = null;

    public function render(OhmgApiService $apiService)
    {
        // You can add filtering logic here for archived batches
        $data = $this->loadAllBatches($apiService, true);

        return view('pages.portal.⚡archive', $data);
    }
};
?>

<div class="flex flex-col">
    <h1 class="text-2xl font-bold mt-4 mb-4 text-center">Archive Batches</h1>

    <div class="md:flex justify-center">
        <x-batch-list
            title="Script Batches"
            :batches="$batches"
            type="script"
            :openId="$openId"
            toggleMethod="toggle" />

        <x-batch-list
            title="AA Batches"
            :batches="$aa_batches"
            type="aa"
            :openId="$aaId"
            toggleMethod="aa_toggle" />
    </div>
</div>
