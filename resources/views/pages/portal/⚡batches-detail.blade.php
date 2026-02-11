<?php

use Livewire\Component;

new class extends Component
{
    public $id;

    public function render()
    {
        $myUrl = 'https://test.onholdmediagroup.com/api/batches/scripts/' . $this->id;
        $token = config('services.ohmg.token');

        $response = Http::withToken($token, 'Token')->get($myUrl);

        $data = $response->json();
        return view('pages.portal.⚡batches-detail', ['data' => $data]);
    }
};
?>

<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="space-y-4">
        @forelse($data as $datum)
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700 overflow-hidden hover:shadow-md transition-shadow">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-zinc-700 dark:to-zinc-700 px-5 py-3 border-b border-gray-200 dark:border-zinc-600">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">Script #{{ $datum['script_id'] }}</h3>
                </div>
                <div class="px-5 py-4">
                    <div class="prose prose-sm max-w-none dark:prose-invert text-gray-700 dark:text-gray-300 leading-relaxed">
                        {!! $datum['script'] !!}
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6 text-center">
                <p class="text-yellow-800 dark:text-yellow-200">No scripts found for this batch.</p>
            </div>
        @endforelse
    </div>
</div>
