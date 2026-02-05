<?php

use App\Models\MyFiles;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;

new #[Title('Upload Files')]
class extends Component {
    use WithFileUploads;

    #[Validate('max:4096')] // 4MB Max
    public array $files = [];

    #[Url(as: 'batch_id')]
    public string $batchId;

    public function batch_route()
    {
        $batch_id = (int)str_replace(['aa-', 's-'], '', $this->batchId);
        if (str_contains($this->batchId, 's-'))
            return ['type' => 's', 'batch_id' => $batch_id];
        else
            return ['type' => 'aa', 'batch_id' => $batch_id];
    }

    public function save()
    {
        $this->validate();

        foreach ($this->files as $file) {
            $originalName = $file->getClientOriginalName();
            $batchRoute = $this->batch_route();

            Log::info('Uploading file', [
                'original' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getClientMimeType(),
            ]);

            try {

                if ($batchRoute['type'] === 's') {
                    $subPath = 'voice-files';
                } else {
                    $subPath = 'aa-files/' . $batchRoute['batch_id'];
                }

//                $path = $file->storeAs($subPath, $originalName, 'public');
                $path = $file->storeAs($subPath, $originalName, 's3');

                // sanity check: confirm it exists on s3
                $exists = Storage::disk('s3')->exists($path);

                Log::info('S3 store result', [
                    'path' => $path,
                    'exists' => $exists,
                    'bucket' => config('filesystems.disks.s3.bucket'),
                    'region' => config('filesystems.disks.s3.region'),
                ]);

                if (!$exists) {
                    throw new RuntimeException("S3 upload returned path but object not found: {$path}");
                }

                MyFiles::create([
                    'name' => $originalName,
                    'user_id' => auth()->id(),
                    'size' => $file->getSize(),
                    'path' => $path,
                    'mime_type' => $file->getClientMimeType(),
                    'batch_id' => $this->batchId
                ]);
            } catch (Throwable $e) {
                Log::error('Upload failed', [
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
        return view('pages.portal.⚡upload', ['files' => $this->files, 'batchId' => $this->batchId]);
    }
};
?>

<div>
    <h1 class="text-2xl font-bold text-center mb-4 mt-4">Upload Audio ({{ $batchId }})</h1>
    @if (session()->has('message'))
        <div class="text-center mx-8 my-8  border-l-4 border-green-500 bg-green-100 p-4 text-green-700 opacity-75">
            {{ session('message') }}
        </div>
    @endif
    <form wire:submit="save">
        <div class="flex justify-center">
            <div>
                <div>
                    <input type="file" name="file" class="rounded-md border border-dashed p-16 bg-slate-50" wire:model="files" multiple>
                    @error('file') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="mt-2 flex justify-end">
                    <button type="submit"
                            class="rounded-md bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4">Upload
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
