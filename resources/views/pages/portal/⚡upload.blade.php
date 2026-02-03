<?php

use App\Models\MyFiles;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;

new class extends Component {
    use WithFileUploads;

    #[Validate('max:4096')] // 4MB Max
    public array $files = [];

    public function save()
    {
        $this->validate();

        foreach ($this->files as $file) {
            $originalName = $file->getClientOriginalName();
            try {
                $path = $file->storeAs('vt-uploads', $originalName, 'public');

                // sanity check: confirm it exists on s3
//                $exists = Storage::disk('public')->exists($path);
//                Log::info('S3 upload result', ['path' => $path, 'exists' => $exists]);

                MyFiles::create([
                    'name' => $originalName,
                    'user_id' => auth()->id(),
                    'size' => $file->getSize(),
                    'path' => $path,
                    'mime_type' => $file->getClientMimeType(),
                ]);
            } catch (\Throwable $e) {
                Log::error('S3 upload failed', [
                    'message' => $e->getMessage(),
                    'file' => $originalName,
                ]);

                throw $e; // so you see the real error in dev
            }

        }

        session()->flash('message', 'Files uploaded successfully!');
    }

    public function render()
    {
        return view('pages.portal.⚡upload', ['files' => $this->files]);
    }
};
?>

<div>
    <h1 class="text-2xl font-bold text-center mb-4 mt-4">Upload Audio</h1>
    @if (session()->has('message'))
        <div class="text-center mx-8 my-8  border-l-4 border-green-500 bg-green-100 p-4 text-green-700 opacity-75">
            {{ session('message') }}
        </div>
    @endif
    <form wire:submit="save">
        <div class="flex justify-center">
            <div>
                <div>
                    <input type="file" name="file" class="border border-dashed p-4" wire:model="files" multiple>
                    @error('file') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="mt-2 flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4">Upload
                    </button>
                </div>
            </div>
        </div>
    </form>
    @if($files)
        @foreach($files as $file)
            <div class="mt-4 text-center">
                <p class="text-gray-700">{{ $file->getClientOriginalName() }}</p>
            </div>
        @endforeach
    @endif
</div>
