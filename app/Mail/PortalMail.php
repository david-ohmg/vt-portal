<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PortalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Portal Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.test',
        );
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $address = 'david@onholdwizard.com';
        $subject = 'This is a demo!';
        $name = 'Jane Doe';

        return $this->view('emails.test')
            ->from($address, $name)
//            ->cc($address, $name)
//            ->bcc($address, $name)
            ->replyTo('vt@onholdwizard.com', 'OHMG VT Portal')
            ->subject($subject)
            ->with([ 'test_message' => $this->data['message'] ]);
    }


    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
