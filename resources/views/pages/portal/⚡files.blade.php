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
    <div class="w-full justify-center mx-auto px-4">
        <table class="table-auto w-full text-left whitespace-no-wrap">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Created At</th>
                    <th>Size</th>
                    <th>MIME Type</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($files as $file)
                    <tr>
                        <td class="mr-4">{{ $file->name }}</td>
                        <td class="text-gray-400">{{ $file->created_at }}</td>
                        <td class="text-gray-400">{{ $file->size }}</td>
                        <td class="text-gray-400">{{ $file->mime_type }}</td>
                    </tr>
                @empty
                    <td class="colspan-4">No files yet</td>
                @endforelse
            </tbody>
        </table>

    </div>
</div>
