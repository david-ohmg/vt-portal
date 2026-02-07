@props(['title', 'batches', 'type', 'openId', 'toggleMethod'])

<div class="list px-4 w-full">
    <h2 class="text-lg font-bold mb-2 text-center">{{ $title }}</h2>
    <div class="max-h-150 overflow-y-auto pr-2">
        @forelse($batches as $batch)
            <x-batch-item
                :batch="$batch"
                :type="$type"
                :openId="$openId"
                wire:click="{{ $toggleMethod }}({{ $batch['id'] }})" />

        @empty
            <div class="text-xs w-full flex gap-2 mb-4 py-2 items-center rounded-md bg-green-100 border border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 px-4">
                No {{ strtolower($title) }} found
            </div>
        @endforelse
    </div>
</div>
