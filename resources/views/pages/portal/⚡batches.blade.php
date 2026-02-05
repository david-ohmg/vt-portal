<?php

use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;
use Carbon\Carbon;

new #[Title('My Batches')]
class extends Component {
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

    public function get_batches(?int $user_id)
    {
        if ($user_id and $user_id > 0) {
            $myUrl = 'https://test.onholdmediagroup.com/api/batches/batches/?female_vt='.$user_id;
        } else {
            $myUrl = 'https://test.onholdmediagroup.com/api/batches/batches/';
//        $myUrl = 'http://localhost:8888/api/batches/batches/';
        }

        $response = Http::withToken(config('services.ohmg.token'), 'Token')->get($myUrl);

        return $response->json(); // array
    }

    public function get_aa_batches(?int $user_id)
    {
        if ($user_id and $user_id > 0) {
            $myUrl = 'https://test.onholdmediagroup.com/api/aa-tracking/aa-tracking/?voice_talent_id='.$user_id;
//            $myUrl = 'http://localhost:8888/api/aa-tracking/aa-tracking/?voice_talent_id='.$user_id;
        } else {
            $myUrl = 'https://test.onholdmediagroup.com/api/aa-tracking/aa-tracking/';
//        $myUrl = 'http://localhost:8888/api/aa-tracking/aa-tracking/';
        }

        $response = Http::withToken(config('services.ohmg.token'), 'Token')->get($myUrl);

        return $response->json(); // array
    }

    public function render()
    {
        $user_id = User::where('email', auth()->user()->getEmailForVerification())->value('vt_id');
        $batches = $this->get_batches($user_id);
        $aa_batches = $this->get_aa_batches($user_id);
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
            <h2 class="text-lg font-bold mb-2 text-center">AA Batches</h2>
            @forelse($aa_batches as $aa)
                <div
                    class="text-xs w-full flex gap-2 mb-4 py-2 items-center rounded-md bg-slate-100 border border-gray-300 px-4 hover:bg-gray-200 hover:text-slate-900 cursor-pointer"
                    wire:click="aa_toggle({{ $aa['id'] }})">
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
                            <a href="{{ route('portal.upload', ['batch_id' => 'aa-'.$aa['id']]) }}"
                               class="hover:text-blue-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M7.5 7.5h-.75A2.25 2.25 0 0 0 4.5 9.75v7.5a2.25 2.25 0 0 0 2.25 2.25h7.5a2.25 2.25 0 0 0 2.25-2.25v-7.5a2.25 2.25 0 0 0-2.25-2.25h-.75m0-3-3-3m0 0-3 3m3-3v11.25m6-2.25h.75a2.25 2.25 0 0 1 2.25 2.25v7.5a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-.75"/>
                                </svg>
                            </a>
                            <a href="{{ route('portal.files', ['batch_id' => 'aa-'.$aa['id']]) }}" title="My Files"
                               class="hover:text-blue-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/>
                                </svg>
                            </a>
                            <a href="{{ $aa['rfp_file'] }}" title="View RFP File"
                               class="text-sm text-shadow-black hover:text-blue-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                                </svg>
                            </a>
                            @if($aa['qc_1_date'])
                                <span class="text-green-500" title="Complete">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="size-6">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
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
                <div
                    class="text-xs w-full flex gap-2 mb-4 py-2 items-center rounded-md bg-green-100 border border-gray-300 px-4">
                    No AA Batches
                </div>
            @endforelse
        </div>

        <div class="list px-4 w-full">
            <h2 class="text-lg font-bold mb-2 text-center">Script Batches</h2>
            @forelse ($batches as $batch)
                <div
                    class="text-xs w-full flex gap-2 mb-4 items-center rounded-md bg-slate-100 border border-gray-300 px-4 hover:bg-gray-200 hover:text-slate-900 cursor-pointer"
                    wire:click="toggle({{ $batch['id'] }})">
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
                                <span class="p-2 rounded-md m-2 text-xs bg-amber-400 text-white font-bold">
                            @elseif($batch['priority'] === 2)
                                        <span class="p-2 rounded-md m-2 text-xs bg-red-400 text-white font-bold">
                            @else
                                                <span
                                                    class="p-2 rounded-md m-2 text-xs bg-blue-400 text-white font-bold">
                            @endif
                                                    {{ $batch['priority_string'] }}
                            </span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex gap-2 items-center">
                            <a href="{{ route('portal.upload', ['batch_id' => 's-'.$batch['id']]) }}"
                               title="Upload files for this batch" class="hover:text-blue-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M7.5 7.5h-.75A2.25 2.25 0 0 0 4.5 9.75v7.5a2.25 2.25 0 0 0 2.25 2.25h7.5a2.25 2.25 0 0 0 2.25-2.25v-7.5a2.25 2.25 0 0 0-2.25-2.25h-.75m0-3-3-3m0 0-3 3m3-3v11.25m6-2.25h.75a2.25 2.25 0 0 1 2.25 2.25v7.5a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-.75"/>
                                </svg>
                            </a>
                            <a href="{{ route('portal.files', ['batch_id' => 's-'.$batch['id']]) }}"
                               title="My Files for batch {{ $batch['id'] }}" class="hover:text-blue-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/>
                                </svg>
                            </a>
                            <a href="{{ $batch['rfp_file_url'] }}" title="View RFP File"
                               class="text-sm text-shadow-black hover:text-blue-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                                </svg>
                            </a>
                            @if($batch['date_qc1'])
                                <span class="text-green-500" title="Complete">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="size-6">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
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
            @empty
                <div
                    class="text-xs w-full flex gap-2 mb-4 py-2 items-center rounded-md bg-green-100 border border-gray-300 px-4">
                    No batches found
                </div>
            @endforelse
        </div>
    </div>

</div>
