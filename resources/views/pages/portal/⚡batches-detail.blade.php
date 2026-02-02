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

<div>
    <ul class="flex flex-col gap-2 mb-4 text-sm text-gray-500 border border-gray-300 py-2 px-4">
        @foreach($data as $datum)
            <li>{{ $datum['script_id'] }}</li>
            <li>{{ $datum['script'] }}</li>
        @endforeach
    </ul>
</div>
