@props(['batch', 'openId', 'type' => 'script'])

@php
    $batchId = $type === 'aa' ? 'aa-' . $batch['id'] : 's-' . $batch['id'];
    $priorityColors = [
        1 => 'bg-yellow-400 text-white dark:bg-yellow-500 dark:text-black',
        2 => 'bg-red-400 text-white dark:bg-red-500 dark:text-black',
    ];
    $defaultPriority = 'bg-blue-400 text-white dark:bg-blue-500 dark:text-black';
    $priorityClass = $priorityColors[$batch['priority']] ?? $defaultPriority;
    $isComplete = $type === 'aa' ? $batch['qc_1_date'] ?? false : $batch['date_qc1'] ?? false;
@endphp

<div class="w-full mb-4 rounded border border-gray-300 bg-zinc-100 dark:bg-zinc-800 dark:border-zinc-700 px-4 py-4">
    <div class="text-xs w-full flex gap-2 mb-4 items-center rounded-md bg-zinc-200 border border-gray-300 px-4 py-4 hover:bg-gray-200 hover:text-slate-900 cursor-pointer dark:bg-zinc-800 dark:border-zinc-700 dark:hover:bg-zinc-700 dark:hover:text-white"
        {{ $attributes }}>
        <div class="flex-1">
            <span class="text-xs text-gray-700 dark:text-gray-200">{{ $batch['id'] }}</span>
        </div>
        <div class="flex-2">
            {{ $type === 'aa' ? $batch['customer_name'] : $batch['category_details'] }}
        </div>
        <div class="flex-1">
            {{ Carbon\Carbon::parse($batch['date_entered'])->format('M j, Y') }}
        </div>
        <div class="flex-1">
            {{ $type === 'aa' ? $batch['voice_talent_details'] : $batch['female_vt_details'] }}
        </div>
    </div>
    @if($openId === $batch['id'])
        <div>
            @livewire('pages::portal.' . ($type === 'aa' ? 'aa-' : '') . 'batches-detail',
                ['id' => $batch['id']],
                key('detail-' . $batch['id']))
        </div>
    @endif
    <div class="flex w-full items-center justify-between">
        <span class="p-1 rounded-md m-2 text-xs {{ $priorityClass }} font-medium">
            {{ $batch['priority_string'] }}
        </span>
        <x-batch-actions
            :batchId="$batchId"
            :hasScripts="$batch['n_scripts'] > 0"
            :n_files="0"
            :rfpUrl="$type === 'aa' ? $batch['rfp_file'] : $batch['rfp_file_url']"
            :isComplete="$isComplete" />
    </div>
</div>
