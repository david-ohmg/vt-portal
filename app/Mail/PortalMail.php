<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PortalMail extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * Create a new message instance.
     */
    public function __construct(
        public readonly array $data
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            cc: [
                new Address('david@onholdwizard.com', 'David'),
                new Address('whitney@onholdwizard.com', 'Whitney'),
                new Address('gary@onholdwizard.com', 'Gary'),
                new Address('brian@onholdwizard.com', 'Hoff'),
                new Address('vt@onholdwizard.com', 'Phone Recordings')
            ],
            replyTo: [new Address('vt@onholdwizard.com', 'VT Portal')],
            subject: $this->data['subject'],
        // Optional: set a from address here, otherwise MAIL_FROM_* is used.
        // from: new Address('no-reply@example.com', 'VT Portal'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.test',
            with: [
                'heading' => $this->data['heading'] ?? $this->data['subject'] ?? 'Notification',
                'emailMessage' => $this->data['message'] ?? $this->data['test_message'] ?? '',
                'actionUrl' => $this->data['actionUrl'] ?? null,
                'actionText' => $this->data['actionText'] ?? null,
            ],
        );
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
