<?php

use App\Mail\PortalMail;
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

    public function batchRoute(): array
    {
        $batch_id = (int)str_replace(['aa-', 's-'], '', $this->batchId);
        $label = str_contains($this->batchId, 's-') ? 's' : 'aa';
        return ['type' => $label, 'batch_id' => $batch_id];
    }

    public function batchDetail()
    {
        $batchRoute = $this->batchRoute();
        $url = config('services.ohmg.url') . 'batches/batches/' . $batchRoute['batch_id'];
        $token = config('services.ohmg.token');
        $response = Http::withToken($token, 'Token')->get($myUrl);
        return $response->json();
    }

    public function sendMail($email, $body): void
    {
        Mail::to($email)->send(new PortalMail(
            ['subject' => 'Files Uploaded for ' . $this->batchId, 'message' => $body]
        ));
    }

    public function save()
    {
        $this->validate();
        $body = '';
        foreach ($this->files as $file) {
            $originalName = $file->getClientOriginalName();
            $batchRoute = $this->batchRoute();

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

                $path = $file->storeAs($subPath, $originalName, 'public');
//                $path = $file->storeAs($subPath, $originalName, 's3');

                // append the body of the email
                $body += $originalName . ' - ' . $path;

                // sanity check: confirm it exists on disk
                $exists = Storage::disk('public')->exists($path);

                Log::info('File store result', [
                    'path' => $path,
                    'exists' => $exists,
                    'bucket' => config('filesystems.disks.s3.bucket'),
                    'region' => config('filesystems.disks.s3.region'),
                ]);

                if (!$exists) {
                    throw new RuntimeException("File upload returned path but object not found: {$path}");
                }

                // create MyFiles model
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

        // get batch detail and send email for processed files
        $data = $this->batchDetail();
        $this->sendMail($data['writer_details'], $body);

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
                    <input type="file" name="file"
                           class="rounded-md border border-dashed p-16 bg-slate-50 dark:bg-slate-700" wire:model="files"
                           multiple>
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
