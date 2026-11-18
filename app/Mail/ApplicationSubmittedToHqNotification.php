<?php

namespace App\Mail;

use App\Models\RentalContract;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationSubmittedToHqNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public RentalContract $contract,
        public User $submittedBy,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'SPEKTRA: PERMOHONAN BAHARU UNTUK SEMAKAN IBU PEJABAT',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.spektra-notice',
            with: [
                'paragraphs' => [
                    'Dimaklumkan bahawa permohonan berkenaan kini berada dalam status Semakan Permohonan (Ibu Pejabat).',
                ],
                'includeCatatan' => true,
                'tindakanLanjut' => null,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
