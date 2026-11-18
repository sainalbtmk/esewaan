<?php

namespace App\Mail;

use App\Models\RentalContract;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationSubmittedToAdminNegeriNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public RentalContract $contract,
        public User $submittedBy,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'SPEKTRA: PERMOHONAN DIHANTAR KE IBU PEJABAT UNTUK SEMAKAN',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.spektra-notice',
            with: [
                'paragraphs' => [
                    'Dimaklumkan bahawa permohonan berkenaan kini berada dalam status Semakan Permohonan (Ibu Pejabat).',
                    'Sehubungan itu, mohon pihak tuan/puan untuk membuat semakan lanjut ke atas permohonan tersebut melalui sistem SPEKTRA.',
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
