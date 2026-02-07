<?php

use App\Models\MyFiles;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Carbon\Carbon;

new #[Title('My Files')]
class extends Component
{

    #[Url(as: 'batch_id')]
    public string $batchId;

    public function render()
    {
        $files = MyFiles::where('batch_id', $this->batchId)->get();
        return view('pages.portal.⚡files', ['files' => $files]);
    }
};
?>

<div>
    <h1 class="text-2xl mt-4 mb-4 font-bold text-center">My Files ({{ $batchId }})</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
        @forelse ($files as $file)
            <div class="flex flex-col gap-2 rounded-lg border border-gray-300 bg-zinc-100 dark:bg-zinc-800 dark:border-zinc-700 p-4 hover:shadow-lg transition-shadow">
                <div class="font-semibold text-lg truncate" title="{{ $file->name }}">
                    {{ $file->name }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                        <span>{{ Carbon::parse($file->created_at)->format('M j, Y') }}</span>
                    </div>
                    <div class="flex items-center gap-2 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        <span>{{ number_format($file->size / 1024, 2) }} KB</span>
                    </div>
                    <div class="flex items-center gap-2 mt-1 text-xs break-all" title="{{ $file->path }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                        </svg>
                        <span class="truncate">{{ $file->path }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full flex items-center justify-center gap-2 p-4 rounded-md border border-red-300 bg-red-100 dark:border-red-700 dark:bg-red-900/20 text-red-700 dark:text-red-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
                No files yet
            </div>
        @endforelse
    </div>
</div>
