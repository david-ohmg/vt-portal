<?php

use Livewire\Component;

new class extends Component {
    public ?int $openId = null;

    public function toggle($id)
    {
        $this->openId = $this->openId === $id ? null : $id;
    }

    public function render() {
        $myUrl = 'https://test.onholdmediagroup.com/api/batches/batches/';

        $response = Http::withToken(config('services.ohmg.token'), 'Token')->get($myUrl);

        $data = $response->json(); // array
        return view('pages.portal.⚡batches', ['data' => $data]);
    }
};
?>
<div>
    <h1 class="text-2xl font-bold mt-4 mb-4 text-center">My Batches</h1>
    <div class="justify-center">
        <div class="list px-4">
            @foreach ($data as $d)
                <div class="w-full flex gap-2 mb-4 border border-gray-300 px-4 hover:bg-gray-200 hover:text-slate-900 cursor-pointer" wire:click="toggle({{ $d['id'] }})">
                    <div class="flex-1/2">
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
