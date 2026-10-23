<?php

namespace App\Mail;

use App\Support\ContractExpiryReminderTier;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ContractExpiryReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  Collection<int, \App\Models\RentalContract>  $contracts
     */
    public function __construct(
        public Collection $contracts,
        public ContractExpiryReminderTier $tier,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->tier->subject(),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contract-expiry-reminder',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
