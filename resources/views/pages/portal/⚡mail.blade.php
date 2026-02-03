<?php

use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use App\Mail\PortalMail;

new class extends Component
{
    public $message;

    public function sendMail() {
        Mail::to('david@onholdwizard.com')->send(new PortalMail(
            ['subject' => 'Test Mail', 'message' => $this->message]
        ));
    }
};
?>

<div>
    <form wire:submit="sendMail" method="POST">
        @csrf
        <textarea name="message" id="message" cols="30" rows="10" wire:model="message"></textarea>
        <button type="submit">Send Mail</button>
    </form>
</div>
