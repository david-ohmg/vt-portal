<?php

use Livewire\Component;

new class extends Component {
    public ?int $openId = null;

    public function toggle($id)
    {
        $this->openId = $this->openId === $id ? null : $id;
    }

    public function render() {
        //$myUrl = 'https://test.onholdmediagroup.com/api/batches/batches/';
        $myUrl = 'http://localhost:8888/api/batches/batches/';

        $response = Http::withToken(config('services.ohmg.token'), 'Token')->get($myUrl);

        $data = $response->json(); // array
        return view('pages.portal.⚡batches', ['data' => $data]);
    }
};
?>
<div class="flex flex-col">
    <h1 class="text-2xl font-bold mt-4 mb-4 text-center">My Batches</h1>
    <div class="justify-center">
        <div class="list px-4">
            @foreach ($data as $d)
                <div class="w-full flex gap-2 mb-4 border border-gray-300 px-4 hover:bg-gray-200 hover:text-slate-900 cursor-pointer" wire:click="toggle({{ $d['id'] }})">
                    <div class="flex-1">
                        {{ $d['id'] }}
                    </div>
                    <div class="flex-1">
                        {{ $d['category_details'] }}
                    </div>
                    <div class="flex-1">
                        {{ $d['writer_details'] }}
                    </div>
                    <div class="flex-1">
                        {{ $d['female_vt_details'] }}
                    </div>
                    <div class="flex-1">
                        <div class="flex gap-2">
                            <a href="{{ $d['rfp_file_url'] }}" title="View RFP File">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                            </a>
                            @if($d['date_qc1'])
                                <span class="text-green-500" title="Complete">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                </span>
                            @endif
                        </div>

                    </div>
                </div>
                @if ($openId === $d['id'])
                    <div>
                        @livewire('pages::portal.batches-detail', ['id' => $d['id']], key('detail-'.$d['id']))
                    </div>
                @endif
            @endforeach
        </div>
    </div>

</div>
