<?php

use App\Models\MyFiles;
use Livewire\Component;

new class extends Component {
    public function render()
    {
        $files = MyFiles::where('user_id', auth()->user()->id)->get();
        return view('pages.portal.⚡files', ['files'=>$files]);
    }
};
?>

<div>
    <h1 class="text-2xl mt-4 mb-4 font-bold text-center">My Files</h1>
    <div class="px-8">
        <div class="flex gap-4 justify-center rounded-md border-gray-200 border bg-slate-100 p-2">
            <div class="flex-1 flex-col">Name</div>
            <div class="flex-1 flex-col">Created At</div>
            <div class="flex-1 flex-col">Size</div>
            <div class="flex-1 flex-col">MIME Type</div>
        </div>

        @forelse ($files as $file)
            <div class="flex gap-4 p-2 rounded-md border-gray-200 border bg-slate-200 mt-4">
                <div class="mr-4 flex-1 flex-col">{{ $file->name }}</div>
                <div class="text-gray-400 flex-1 flex-col">{{ $file->created_at }}</div>
                <div class="text-gray-400 flex-1 flex-col">{{ $file->size }}</div>
                <div class="text-gray-400 flex-1 flex-col">{{ $file->mime_type }}</div>
            </div>
        @empty
            <div class="colspan-4">No files yet</div>
        @endforelse
    </div>
</div>
