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

    public const AUDIENCE_STANDARD = 'standard';

    public const AUDIENCE_SEGERA = 'segera';

    public const AUDIENCE_AMARAN = 'amaran';

    /**
     * @param  Collection<int, \App\Models\RentalContract>  $contracts
     */
    public function __construct(
        public Collection $contracts,
        public ContractExpiryReminderTier $tier,
        public string $audience = self::AUDIENCE_STANDARD,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectLine(),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: match ($this->tier) {
                ContractExpiryReminderTier::EightMonth => 'emails.contract-extension-reminder',
                ContractExpiryReminderTier::ThreeMonth => 'emails.contract-three-month-warning',
                default => 'emails.contract-expiry-reminder',
            },
            with: [
                'heading' => $this->heading(),
                'intro' => $this->intro(),
            ],
        );
    }

    public function subjectLine(): string
    {
        if ($this->tier !== ContractExpiryReminderTier::ThreeMonth) {
            return $this->tier->subject();
        }

        return $this->audience === self::AUDIENCE_SEGERA
            ? 'SPEKTRA: Senarai Ruang Pejabat Hampir Tamat Tempoh dan Perlu Tindakan SEGERA'
            : 'SPEKTRA: AMARAN Senarai Ruang Pejabat Hampir Tamat Tempoh dan Perlu Tindakan SEGERA';
    }

    public function heading(): string
    {
        if ($this->audience === self::AUDIENCE_SEGERA) {
            return 'Senarai Ruang Pejabat Hampir Tamat Tempoh dan Perlu Tindakan SEGERA';
        }

        return 'AMARAN: Senarai Ruang Pejabat Hampir Tamat Tempoh dan Perlu Tindakan SEGERA';
    }

    public function intro(): string
    {
        if ($this->audience === self::AUDIENCE_SEGERA) {
            return 'Berikut ialah senarai ruang pejabat yang berbaki 3 bulan atau kurang dan memerlukan tindakan SEGERA.';
        }

        return 'AMARAN untuk negeri yang terlibat sahaja. Ruang pejabat berikut berbaki 3 bulan atau kurang dan memerlukan tindakan Pindah atau Lanjutan.';
    }

    public function attachments(): array
    {
        return [];
    }
}
