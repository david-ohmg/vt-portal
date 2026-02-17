<?php

use App\Services\FileUploadService;
use App\Services\OhmgApiService;
use App\Services\UploadNotificationService;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Title('Upload Files')]
class extends Component {
    use WithFileUploads;

    #[Validate('max:4096')] // 4MB Max
    public array $files = [];

    #[Url(as: 'batch_id')]
    public string $batchId;

    public function save(
        FileUploadService $uploadService,
        OhmgApiService $apiService,
        UploadNotificationService $notificationService
    ) {
        $this->validate();

        $uploadedPaths = [];

        // Upload all files
        foreach ($this->files as $file) {
            $path = $uploadService->uploadFile($file, $this->batchId, auth()->id());
            $uploadedPaths[] = $path;
        }

        // Update batch status
        $batchRoute = $uploadService->parseBatchId($this->batchId);
        $apiService->updateBatch(
            $batchRoute['type'],
            $batchRoute['batch_id'],
            [
                'emp_recorded' => 8,
                'date_recorded' => now()->toDateString(),
            ]
        );

        // Send notification email
        $batchDetail = $apiService->getBatchDetail($batchRoute['type'], $batchRoute['batch_id']);
        $writerEmail = $batchDetail['writer_details'] ?? null;

        $vtEmail = $batchRoute['type'] === 'aa' ? $batchDetail['vt_email'] : $batchDetail['female_vt_email'];;

        $customerName = $batchRoute['type'] === 'aa' ? $batchDetail['customer_name'] : $batchDetail['category_details'];

        if ($writerEmail) {
            $notificationService->sendUploadNotification($writerEmail, $vtEmail, $customerName, $this->batchId, $uploadedPaths);
        }

        // Reset form and show success
        $this->files = [];
        session()->flash('message', 'Files uploaded successfully!');
    }

    public function render()
    {
        return view('pages.portal.⚡upload');
    }
};
?>

<div>
    <h1 class="text-2xl font-bold text-center mb-4 mt-4">Upload Audio ({{ $batchId }})</h1>

    @if (session()->has('message'))
        <div class="text-center mx-8 my-8 border-l-4 border-green-500 bg-green-100 p-4 text-green-700 opacity-75">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit="save">
        <div class="flex justify-center">
            <div class="w-full max-w-2xl">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">
                        Select Audio Files
                    </label>
                    <input
                        type="file"
                        wire:model="files"
                        multiple
                        accept="audio/*"
                        class="w-full rounded-md border border-dashed p-16 bg-slate-50 dark:bg-slate-700 hover:bg-slate-100 dark:hover:bg-slate-600 cursor-pointer transition">
                    @error('files.*')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                @if($files)
                    <div class="mb-4 p-4 bg-white dark:bg-zinc-800 rounded-md border">
                        <h3 class="font-semibold mb-2">Selected Files ({{ count($files) }})</h3>
                        <ul class="space-y-2">
                            @foreach($files as $file)
                                <li class="flex items-center gap-2 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">{{ $file->getClientOriginalName() }}</span>
                                    <span class="text-gray-500 text-xs">({{ number_format($file->getSize() / 1024, 2) }} KB)</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex justify-end gap-2">
                    <a href="{{ route('portal.batches') }}"
                       class="rounded-md bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4">
                        Cancel
                    </a>
                    <button
                        type="submit"
                        @disabled(!$files)
                        class="rounded-md bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 disabled:opacity-50 disabled:cursor-not-allowed">
                        Upload {{ count($files) > 0 ? '(' . count($files) . ')' : '' }}
                    </button>
                </div>
            </div>
        </div>
    </form>

    <div wire:loading wire:target="save" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-xl">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div>
            <p class="mt-4 text-center font-semibold">Uploading files...</p>
        </div>
    </div>
</div>
