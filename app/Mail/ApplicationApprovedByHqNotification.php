<?php

namespace App\Mail;

use App\Models\RentalContract;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationApprovedByHqNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  'hq'|'negeri'  $audience
     */
    public function __construct(
        public RentalContract $contract,
        public User $approvedBy,
        public string $audience = 'negeri',
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'SPEKTRA: PERMOHONAN DILULUSKAN OLEH BAHAGIAN PENGURUSAN HARTANAH, JABATAN PERDANA MENTERI',
        );
    }

    public function content(): Content
    {
        $date = $this->contract->hq_approved_at
            ?->timezone('Asia/Kuala_Lumpur')
            ->format('d/m/Y') ?? '–';

        $paragraphs = [
            'Dimaklumkan bahawa permohonan tuan/puan TELAH DILULUSKAN oleh Bahagian Pengurusan Hartanah, Jabatan Perdana Menteri pada '.$date.'.',
        ];

        if ($this->audience === 'negeri') {
            $paragraphs[] = 'Sehubungan itu, pihak tuan/puan diminta untuk menyediakan satu (1) salinan draf perjanjian penyewaan dan dimuat naik ke dalam Sistem SPEKTRA dalam tempoh tujuh (7) hari bekerja untuk semakan Penasihat Undang-Undang AADK.';
        }

        return new Content(
            view: 'emails.spektra-notice',
            with: [
                'paragraphs' => $paragraphs,
                'includeCatatan' => false,
                'tindakanLanjut' => 'Penyediaan draf Perjanjian',
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
