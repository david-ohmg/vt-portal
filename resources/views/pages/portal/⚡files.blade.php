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
    <div class="w-1/2 justify-center mx-auto">
        @forelse ($files as $file)
            <div class="flex mb-4 justify-left w-full gap-4">
                <div class="mr-4">{{ $file->name }}</div>
                <div class="text-gray-400">{{ $file->created_at }}</div>
                <div class="text-gray-400">{{ $file->size }}</div>
                <div class="text-gray-400">{{ $file->mime_type }}</div>
            </div>
        @empty
            <div class="text-gray-400">No files yet</div>
        @endforelse
    </div>
</div>
