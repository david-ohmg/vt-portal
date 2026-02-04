<?php

use Livewire\Component;

new class extends Component
{
    public $id;

    public function render()
    {
//        $myUrl = 'https://test.onholdmediagroup.com/api/aa-tracking/scripts/' . $this->id;
        $myUrl = 'http://localhost:8888/api/aa-tracking/scripts/' . $this->id;
        $token = config('services.ohmg.token');

        $response = Http::withToken($token, 'Token')->get($myUrl);

        $data = $response->json();
        return view('pages.portal.⚡aa-batches-detail', ['data' => $data]);
    }
};
?>

<div>
    <ul class="flex flex-col gap-2 mb-4 rounded-md text-sm bg-slate-200 text-gray-700 border border-gray-300 py-2 px-4">
        @foreach($data as $datum)
            <li>{{ $datum['label'] }}</li>
            <li>{{ $datum['script'] }}</li>
        @endforeach
    </ul>
</div>
