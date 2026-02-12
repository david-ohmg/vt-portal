@props(['batchId', 'hasScripts', "nScripts", "nFiles", 'rfpUrl', 'isComplete'])

<div class="flex gap-2 items-center">
    @if($hasScripts)
        <a href="{{ route('portal.upload', ['batch_id' => $batchId]) }}"
           title="Upload files for this batch"
           class="hover:text-blue-400 dark:hover:text-blue-300">
            <x-icon-upload />
        </a>
    @else
        <span title="Upload unavailable">
            <x-icon-upload />
        </span>
    @endif

    <a href="{{ route('portal.files', ['batch_id' => $batchId]) }}"
       title="My Files for batch {{ $batchId }}"
       class="hover:text-blue-400 dark:hover:text-blue-300">
        <x-icon-folder />
    </a>

    <a href="{{ $rfpUrl }}"
       title="View RFP File"
       class="text-sm text-shadow-black hover:text-blue-400 dark:hover:text-blue-300">
        <x-icon-download />
    </a>

    @if($isComplete)
        <span class="text-green-500" title="Complete">
            <x-icon-check />
        </span>
    @else
        <span class="text-red-500" title="Incomplete">
            <x-icon-x />
        </span>
    @endif
</div>
