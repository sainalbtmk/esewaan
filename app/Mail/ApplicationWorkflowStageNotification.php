<?php

namespace App\Mail;

use App\Models\RentalContract;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationWorkflowStageNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array{subject: string, paragraphs: list<string>, include_catatan: bool, tindakan_lanjut: ?string}  $payload
     */
    public function __construct(
        public RentalContract $contract,
        public User $actedBy,
        public array $payload,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->payload['subject'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.spektra-notice',
            with: [
                'paragraphs' => $this->payload['paragraphs'],
                'includeCatatan' => $this->payload['include_catatan'],
                'tindakanLanjut' => $this->payload['tindakan_lanjut'],
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
