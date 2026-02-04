<?php

use Livewire\Component;
use Carbon\Carbon;

new class extends Component {
    public ?int $openId = null;
    public ?int $aaId = null;

    public function toggle($id)
    {
        $this->openId = $this->openId === $id ? null : $id;
    }

    public function aa_toggle($id)
    {
        $this->aaId = $this->aaId === $id ? null : $id;
    }

    public function get_batches() {
        $myUrl = 'https://test.onholdmediagroup.com/api/batches/batches/';
//        $myUrl = 'http://localhost:8888/api/batches/batches/';

        $response = Http::withToken(config('services.ohmg.token'), 'Token')->get($myUrl);

        return $response->json(); // array
    }

    public function get_aa_batches() {
        $myUrl = 'https://test.onholdmediagroup.com/api/aa-tracking/aa-tracking/';
//        $myUrl = 'http://localhost:8888/api/aa-tracking/aa-tracking/';
        $response = Http::withToken(config('services.ohmg.token'), 'Token')->get($myUrl);

        return $response->json(); // array
    }

    public function render() {
        $batches = $this->get_batches();
        $aa_batches = $this->get_aa_batches();
        return view(
            'pages.portal.⚡batches',
            ['batches' => $batches, 'aa_batches' => $aa_batches]
        );
    }
};
?>
<div class="flex flex-col">
    <h1 class="text-2xl font-bold mt-4 mb-4 text-center">My Batches</h1>
    <div class="md:flex justify-center">

        <div class="list px-4 w-full">
            @forelse($aa_batches as $aa)
                <div class="text-xs w-full flex gap-2 mb-4 py-2 items-center rounded-md bg-slate-100 border border-gray-300 px-4 hover:bg-gray-200 hover:text-slate-900 cursor-pointer" wire:click="toggle({{ $aa['id'] }})">
                    <div class="flex-1">
                        <span class="text-xs text-gray-700">{{ $aa['id'] }}</span>
                    </div>
                    <div class="flex-2">
                        {{ $aa['customer_name'] }}
                    </div>
                    <div class="flex-1">
                        {{ Carbon::parse($aa['date_entered'])->format('M j, Y') }}
                    </div>
                    <div class="flex-1">
                        <div class="flex gap-2 items-center">
                            <a href="{{ $aa['rfp_file'] }}" title="View RFP File" class="text-sm text-shadow-black hover:text-blue-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                            </a>
                            @if($aa['qc_1_date'])
                                <span class="text-green-500" title="Complete">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @if ($aaId === $aa['id'])
                    <div>
                        @livewire('pages::portal.aa-batches-detail', ['id' => $aa['id']], key('detail-'.$aa['id']))
                    </div>
                @endif
            @empty
                <div class="flex-1">No AA Batches</div>
            @endforelse
        </div>

        <div class="list px-4 w-full">
            @foreach ($batches as $batch)
                <div class="text-xs w-full flex gap-2 mb-4 items-center rounded-md bg-slate-100 border border-gray-300 px-4 hover:bg-gray-200 hover:text-slate-900 cursor-pointer" wire:click="toggle({{ $batch['id'] }})">
                    <div class="flex-1">
                        <span class="text-xs text-gray-700">{{ $batch['id'] }}</span>
                    </div>
                    <div class="flex-2">
                        {{ $batch['category_details'] }}
                    </div>
                    <div class="flex-1">
                        {{ Carbon::parse($batch['date_entered'])->format('M j, Y') }}
                    </div>
                    <div class="flex-1">
                        {{ $batch['female_vt_details'] }}
                    </div>
                    <div class="flex-1">
                        <div class="flex gap-2 items-center">
                            @if($batch['priority'] === 1)
                                <span class="p-2 rounded-md m-2 text-xs bg-green-400 text-white">
                            @elseif($batch['priority'] === 2)
                                <span class="p-2 rounded-md m-2 text-xs bg-red-400 text-white">
                            @else
                                <span class="p-2 rounded-md m-2 text-xs bg-blue-400 text-white">
                            @endif
                                {{ $batch['priority_string'] }}
                            </span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex gap-2 items-center">
                            <a href="{{ $batch['rfp_file_url'] }}" title="View RFP File" class="text-sm text-shadow-black hover:text-blue-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                            </a>
                            @if($batch['date_qc1'])
                                <span class="text-green-500" title="Complete">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @if ($openId === $batch['id'])
                    <div>
                        @livewire('pages::portal.batches-detail', ['id' => $batch['id']], key('detail-'.$batch['id']))
                    </div>
                @endif
            @endforeach
        </div>
    </div>

</div>
