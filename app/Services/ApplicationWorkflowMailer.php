<?php

namespace App\Services;

use App\Mail\ApplicationApprovedByHqNotification;
use App\Mail\ApplicationSubmittedToAdminNegeriNotification;
use App\Mail\ApplicationSubmittedToHqNotification;
use App\Mail\ApplicationWorkflowStageNotification;
use App\Models\EmelJawatan;
use App\Models\RentalContract;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ApplicationWorkflowMailer
{
    private bool $notificationFailed = false;

    public function notificationFailed(): bool
    {
        return $this->notificationFailed;
    }

    public function notifySubmittedToHq(RentalContract $contract, User $submittedBy): void
    {
        $contract->loadMissing(['premise', 'adminNegeriUser', 'submittedBy']);

        $headquarters = $this->adminHqNotificationRecipients();

        foreach ($headquarters as $email) {
            $this->deliver($email, new ApplicationSubmittedToHqNotification($contract, $submittedBy));
        }

        foreach ($this->additionalPositionRecipients($headquarters) as $email) {
            $this->deliver($email, new ApplicationSubmittedToHqNotification($contract, $submittedBy));
        }

        foreach ($this->adminNegeriNotificationRecipients($contract) as $email) {
            $this->deliver($email, new ApplicationSubmittedToAdminNegeriNotification($contract, $submittedBy));
        }
    }

    public function notifyApprovedByHq(RentalContract $contract, User $approvedBy): void
    {
        $contract->loadMissing(['premise', 'adminNegeriUser', 'submittedBy']);

        $negeri = $this->adminNegeriNotificationRecipients($contract);
        $headquarters = $this->adminHqNotificationRecipients();

        foreach ($negeri as $email) {
            $this->deliver($email, new ApplicationApprovedByHqNotification($contract, $approvedBy, 'negeri'));
        }

        foreach ($headquarters as $email) {
            $this->deliver($email, new ApplicationApprovedByHqNotification($contract, $approvedBy, 'hq'));
        }

        foreach ($this->additionalPositionRecipients([...$negeri, ...$headquarters]) as $email) {
            $this->deliver($email, new ApplicationApprovedByHqNotification($contract, $approvedBy, 'hq'));
        }
    }

    public function notifyDraftUploaded(RentalContract $contract, User $actedBy): void
    {
        $round = max(1, (int) $contract->semakan_count);

        $this->notifyAudiences(
            $contract,
            $actedBy,
            $this->notice(
                'SPEKTRA: DRAF PERJANJIAN UNTUK SEMAKAN PENASIHAT UNDANG-UNDANG',
                ['Dimaklumkan bahawa draf Perjanjian telah dimuat naik dan dihantar untuk semakan PUU '.$round.'.'],
            ),
            $this->notice(
                'SPEKTRA: DRAF PERJANJIAN DIHANTAR UNTUK SEMAKAN PENASIHAT UNDANG-UNDANG',
                [
                    'Dimaklumkan bahawa draf Perjanjian telah berjaya dimuat naik dan dihantar untuk semakan PUU '.$round.'.',
                    'Sehubungan itu, mohon pihak tuan/puan untuk membuat semakan lanjut ke atas permohonan tersebut melalui sistem SPEKTRA.',
                ],
            ),
        );
    }

    public function notifyPuuRejected(RentalContract $contract, User $actedBy): void
    {
        $returned = 'Dimaklumkan bahawa draf Perjanjian telah disemak dan dikembalikan untuk tindakan lanjut pihak tuan/puan.';

        $this->notifyAudiences(
            $contract,
            $actedBy,
            $this->notice('SPEKTRA: KUIRI OLEH PENASIHAT UNDANG-UNDANG', [$returned], 'Semak semula'),
            $this->notice(
                'SPEKTRA: KUIRI OLEH PENASIHAT UNDANG-UNDANG',
                [
                    $returned,
                    'Sehubungan itu, mohon pihak tuan/puan untuk mengemukakan dokumen sokongan yang diminta oleh PUU dalam tempoh tujuh (7) hari bekerja kepada Cawangan Pembangunan untuk semakan semula draf Perjanjian.',
                ],
                'Semak semula',
            ),
        );
    }

    public function notifyDraftApproved(RentalContract $contract, User $actedBy): void
    {
        $approved = 'Dimaklumkan bahawa draf Perjanjian telah disemak dan diluluskan tertakluk kepada pindaan seperti ulasan PUU.';

        $this->notifyAudiences(
            $contract,
            $actedBy,
            $this->notice('SPEKTRA: DRAF PERJANJIAN DILULUSKAN OLEH PENASIHAT UNDANG-UNDANG', [$approved]),
            $this->notice(
                'SPEKTRA: DRAF PERJANJIAN DILULUSKAN OLEH PENASIHAT UNDANG-UNDANG',
                [
                    $approved,
                    'Sehubungan itu, mohon pihak tuan/puan untuk menyediakan tiga (3) salinan perjanjian seperti pindaan syor PUU yang telah ditandatangani Pemilik Premis, dan kemukakannya ke Cawangan Pembangunan untuk tandatangan pengurusan AADK dalam tempoh tujuh (7) hari bekerja.',
                ],
            ),
        );
    }

    public function notifyReturnedToHq(RentalContract $contract, User $actedBy): void
    {
        $notice = $this->notice(
            'SPEKTRA: PERJANJIAN UNTUK DITANDATANGANI PENGURUSAN AADK',
            ['Dimaklumkan bahawa tiga (3) salinan Perjanjian telah dihantar untuk tujuan tandatangan oleh Pihak Pengurusan AADK selaku Penyewa.'],
        );

        $this->notifyAudiences($contract, $actedBy, $notice, $notice);
    }

    public function notifySentForStampDuty(RentalContract $contract, User $actedBy): void
    {
        $notice = $this->notice(
            'SPEKTRA: PERJANJIAN UNTUK TINDAKAN MATI SETEM',
            ['Dimaklumkan bahawa tiga (3) Salinan Perjanjian telah selesai ditandatangani dan dihantar ke Negeri untuk tindakan mati setem di Lembaga Hasil Dalam Negeri.'],
        );

        $this->notifyAudiences($contract, $actedBy, $notice, $notice);
    }

    public function notifyAwaitingHqConfirmation(RentalContract $contract, User $actedBy): void
    {
        $this->notifyAudiences(
            $contract,
            $actedBy,
            $this->notice(
                'SPEKTRA: PERJANJIAN SELESAI DIMATI SETEM',
                [
                    'Dimaklumkan bahawa Perjanjian telah selesai dilaksanakan tindakan mati setem di Lembaga Hasil Dalam Negeri. Satu (1) salinan perjanjian telah dihantar ke Ibu Pejabat untuk tujuan rekod.',
                    'Sehubungan itu, dimohon pihak tuan/puan untuk membuat pengesahan penerimaan Perjanjian di peringkat Ibu Pejabat.',
                ],
            ),
            null,
        );
    }

    public function notifyCompleted(RentalContract $contract, User $actedBy): void
    {
        $this->notifyAudiences(
            $contract,
            $actedBy,
            null,
            $this->notice(
                'SPEKTRA: PERJANJIAN SELESAI DAN TELAH DIMASUKKAN KE DALAM SENARAI KONTRAK SEWAAN',
                [
                    'Dimaklumkan bahawa satu (1) Salinan Perjanjian telah diterima dan telah dimuat naik ke dalam Sistem ProMIS.',
                    'Permohonan telah dimasukkan ke dalam Senarai Kontrak Sewaan.',
                ],
            ),
        );
    }

    /**
     * @param  list<string>  $paragraphs
     * @return array{subject: string, paragraphs: list<string>, include_catatan: bool, tindakan_lanjut: ?string}
     */
    private function notice(string $subject, array $paragraphs, ?string $tindakanLanjut = null, bool $includeCatatan = false): array
    {
        return [
            'subject' => $subject,
            'paragraphs' => $paragraphs,
            'include_catatan' => $includeCatatan,
            'tindakan_lanjut' => $tindakanLanjut,
        ];
    }

    /**
     * @param  array{subject: string, paragraphs: list<string>, include_catatan: bool, tindakan_lanjut: ?string}|null  $headquarters
     * @param  array{subject: string, paragraphs: list<string>, include_catatan: bool, tindakan_lanjut: ?string}|null  $negeri
     */
    private function notifyAudiences(RentalContract $contract, User $actedBy, ?array $headquarters, ?array $negeri): void
    {
        $contract->loadMissing(['premise', 'adminNegeriUser', 'submittedBy']);

        $alreadySent = [];

        if ($headquarters !== null) {
            foreach ($this->headquartersNoticeRecipients() as $email) {
                $this->deliver($email, new ApplicationWorkflowStageNotification($contract, $actedBy, $headquarters));
                $alreadySent[strtolower($email)] = true;
            }
        }

        if ($negeri === null) {
            return;
        }

        foreach ($this->adminNegeriNotificationRecipients($contract) as $email) {
            if (isset($alreadySent[strtolower($email)]) && $headquarters === $negeri) {
                continue;
            }

            $this->deliver($email, new ApplicationWorkflowStageNotification($contract, $actedBy, $negeri));
        }
    }

    private function deliver(string $email, Mailable $mailable): void
    {
        try {
            Mail::to($email)->send($mailable);
        } catch (Throwable $exception) {
            $this->notificationFailed = true;

            report($exception);
        }
    }

    /**
     * @return list<string>
     */
    private function headquartersNoticeRecipients(): array
    {
        return $this->deliverableEmails(array_merge(
            $this->adminHqNotificationRecipients(),
            $this->positionNotificationRecipients(),
        ));
    }

    /**
     * @return list<string>
     */
    private function positionNotificationRecipients(): array
    {
        if (filled(config('mail.test_recipient'))) {
            return [];
        }

        return $this->deliverableEmails(
            EmelJawatan::query()->orderBy('susunan')->pluck('email')->all(),
        );
    }

    /**
     * @param  list<string>  $alreadyNotified
     * @return list<string>
     */
    private function additionalPositionRecipients(array $alreadyNotified): array
    {
        $skip = array_map(strtolower(...), $alreadyNotified);

        return array_values(array_filter(
            $this->positionNotificationRecipients(),
            fn (string $email): bool => ! in_array(strtolower($email), $skip, true),
        ));
    }

    /**
     * @return list<string>
     */
    private function adminHqNotificationRecipients(): array
    {
        $testRecipient = config('mail.test_recipient');

        if (filled($testRecipient)) {
            return $this->deliverableEmails([(string) $testRecipient]);
        }

        return $this->deliverableEmails(User::query()
            ->where('role', 'admin_hq')
            ->where('is_active', true)
            ->whereNotNull('email')
            ->pluck('email')
            ->all());
    }

    /**
     * @return list<string>
     */
    private function adminNegeriNotificationRecipients(RentalContract $contract): array
    {
        $testRecipient = config('mail.test_recipient');

        if (filled($testRecipient)) {
            return $this->deliverableEmails([(string) $testRecipient]);
        }

        $contract->loadMissing(['adminNegeriUser', 'premise']);

        $emails = [];

        if (
            filled($contract->adminNegeriUser?->email)
            && $contract->adminNegeriUser->is_active
            && $contract->adminNegeriUser->isAdminNegeri()
        ) {
            $emails[] = $contract->adminNegeriUser->email;
        }

        $negeri = $contract->premise?->negeri;

        if (filled($negeri)) {
            $emails = array_merge($emails, User::query()
                ->where('role', 'admin_negeri')
                ->where('negeri', $negeri)
                ->where('is_active', true)
                ->whereNotNull('email')
                ->pluck('email')
                ->all());
        }

        return $this->deliverableEmails($emails);
    }

    /**
     * @param  list<mixed>  $emails
     * @return list<string>
     */
    private function deliverableEmails(array $emails): array
    {
        $valid = [];
        $seen = [];

        foreach ($emails as $email) {
            $email = trim((string) $email);
            $key = strtolower($email);

            if ($email === '' || isset($seen[$key])) {
                continue;
            }

            $seen[$key] = true;

            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $this->notificationFailed = true;

                report(new \RuntimeException('Penerima notifikasi emel tidak sah.'));

                continue;
            }

            $valid[] = $email;
        }

        return $valid;
    }
}
