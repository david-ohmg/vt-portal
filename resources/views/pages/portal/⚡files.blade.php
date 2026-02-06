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
    <div class="px-8">
        <div class="flex gap-4 justify-center rounded-md border-gray-200 border bg-slate-100 dark:border-zinc-700 dark:bg-zinc-800 dark: p-2">
            <div class="flex-1 flex-col">Name</div>
            <div class="flex-1 flex-col">Created At</div>
            <div class="flex-1 flex-col">Size</div>
            <div class="flex-1 flex-col">Path</div>
        </div>

        @forelse ($files as $file)
            <div class="flex gap-4 p-2 rounded-md border-gray-200 border bg-slate-200 dark:border-zinc-700 dark:bg-zinc-800 dark: mt-4">
                <div class="mr-4 flex-1 flex-col">{{ $file->name }}</div>
                <div class="text-gray-400 flex-1 flex-col">{{ Carbon::parse($file->created_at)->format('M j, Y') }}</div>
                <div class="text-gray-400 flex-1 flex-col">{{ $file->size }}</div>
                <div class="text-gray-400 flex-1 flex-col">{{ $file->path }}</div>
            </div>
        @empty
            <div class="colspan-4 flex gap-4 p-2 rounded-md border-gray-200 border bg-red-200 dark:border-zinc-700 dark:bg-zinc-800 dark: mt-4">No files yet</div>
        @endforelse
    </div>
</div>
