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
            $this->deliver($email, new ApplicationApprovedByHqNotification($contract, $approvedBy));
        }

        foreach ($headquarters as $email) {
            $this->deliver($email, new ApplicationApprovedByHqNotification($contract, $approvedBy));
        }

        foreach ($this->additionalPositionRecipients([...$negeri, ...$headquarters]) as $email) {
            $this->deliver($email, new ApplicationApprovedByHqNotification($contract, $approvedBy));
        }
    }

    public function notifyDraftUploaded(RentalContract $contract, User $actedBy): void
    {
        $this->notifyStage($contract, $actedBy, [
            'subject' => 'SPEKTRA: Draf Perjanjian Dihantar untuk Semakan PUU',
            'heading' => 'Dalam Tindakan PUU',
            'intro' => 'Draf perjanjian telah dimuat naik dan dihantar untuk '.$contract->semakanLabel().'.',
            'next_status' => $this->stepperStatus($contract, $contract->semakanLabel()),
            'cta_url' => route('status-permohonan.review', $contract),
            'cta_label' => 'Semak Draf Perjanjian',
        ]);
    }

    public function notifyPuuRejected(RentalContract $contract, User $actedBy): void
    {
        $this->notifyStage($contract, $actedBy, [
            'subject' => 'SPEKTRA: Semakan PUU Memerlukan Pindaan',
            'heading' => 'Kuiri PUU',
            'intro' => 'Semakan PUU dikembalikan untuk pindaan. Negeri perlu muat naik semula draf perjanjian.',
            'next_status' => $this->stepperStatus($contract),
            'cta_url' => route('status-permohonan.review', $contract),
            'cta_label' => 'Lihat Permohonan',
        ]);
    }

    public function notifyDraftApproved(RentalContract $contract, User $actedBy): void
    {
        $this->notifyStage($contract, $actedBy, [
            'subject' => 'SPEKTRA: Draf Perjanjian Diluluskan',
            'heading' => 'Draf Lulus',
            'intro' => 'Draf perjanjian telah diluluskan. Negeri boleh menyediakan dokumen akhir dan mengembalikannya kepada Ibu Pejabat.',
            'next_status' => $this->stepperStatus($contract),
            'cta_url' => route('status-permohonan.review', $contract),
            'cta_label' => 'Lihat Permohonan',
        ]);
    }

    public function notifyReturnedToHq(RentalContract $contract, User $actedBy): void
    {
        $this->notifyStage($contract, $actedBy, [
            'subject' => 'SPEKTRA: Dokumen Dikembalikan untuk Pengesahan & Tandatangan',
            'heading' => 'Pengesahan & Tandatangan',
            'intro' => 'Negeri telah mengembalikan draf akhir kepada Cawangan Pembangunan AADK untuk pengesahan dan tandatangan.',
            'next_status' => $this->stepperStatus($contract),
            'cta_url' => route('status-permohonan.review', $contract),
            'cta_label' => 'Semak Dokumen',
        ]);
    }

    public function notifySentForStampDuty(RentalContract $contract, User $actedBy): void
    {
        $this->notifyStage($contract, $actedBy, [
            'subject' => 'SPEKTRA: Perjanjian Dihantar untuk Mati Setem',
            'heading' => 'Mati Setem',
            'intro' => 'Ibu Pejabat telah mengesahkan tandatangan. Negeri perlu lengkapkan mati setem.',
            'next_status' => $this->stepperStatus($contract),
            'cta_url' => route('status-permohonan.review', $contract),
            'cta_label' => 'Lengkapkan Mati Setem',
        ]);
    }

    public function notifyAwaitingHqConfirmation(RentalContract $contract, User $actedBy): void
    {
        $this->notifyStage($contract, $actedBy, [
            'subject' => 'SPEKTRA: Mati Setem Selesai — Menunggu Pengesahan Ibu Pejabat',
            'heading' => 'Menunggu Pengesahan Ibu Pejabat',
            'intro' => 'Negeri telah menyelesaikan mati setem. Ibu Pejabat perlu mengesahkan penerimaan salinan dan muat naik sistem sebelum permohonan dimasukkan ke Senarai Kontrak Sewaan.',
            'next_status' => $this->stepperStatus($contract),
            'cta_url' => route('status-permohonan.review', $contract),
            'cta_label' => 'Sahkan Permohonan',
        ]);
    }

    public function notifyCompleted(RentalContract $contract, User $actedBy): void
    {
        $this->notifyStage($contract, $actedBy, [
            'subject' => 'SPEKTRA: Permohonan Selesai — Dalam Senarai Kontrak Sewaan',
            'heading' => 'Selesai',
            'intro' => 'Pengesahan Ibu Pejabat selesai. Permohonan dimasukkan ke dalam Senarai Kontrak Sewaan.',
            'next_status' => $this->stepperStatus($contract),
            'cta_url' => route('kontrak-sewaan.show', $contract),
            'cta_label' => 'Lihat Kontrak Sewaan',
        ]);
    }

    /**
     * @param  array{subject: string, heading: string, intro: string, next_status: string, cta_url: string, cta_label: string}  $payload
     */
    private function notifyStage(RentalContract $contract, User $actedBy, array $payload): void
    {
        $contract->loadMissing(['premise', 'adminNegeriUser', 'submittedBy']);

        foreach ($this->workflowRecipients($contract) as $email) {
            $this->deliver($email, new ApplicationWorkflowStageNotification($contract, $actedBy, $payload));
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
    private function workflowRecipients(RentalContract $contract): array
    {
        return $this->deliverableEmails(array_merge(
            $this->adminHqNotificationRecipients(),
            $this->adminNegeriNotificationRecipients($contract),
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

    private function stepperStatus(RentalContract $contract, ?string $detail = null): string
    {
        $index = $contract->draftAgreementCurrentStepIndex();
        $label = $contract->draftAgreementProgressSteps()[$index]['label'] ?? 'Permohonan';
        $detail ??= $this->statusDetail($contract, $label);
        $line = 'Peringkat '.($index + 1).' — '.$label;

        if (filled($detail) && $detail !== $label) {
            $line .= ' ('.$detail.')';
        }

        return $line;
    }

    private function statusDetail(RentalContract $contract, string $stepLabel): ?string
    {
        if ($contract->isPendingAdminReview()) {
            return null;
        }

        $status = $contract->applicationStatusLabel();

        if ($status === '' || $status === $stepLabel || str_contains($stepLabel, $status)) {
            return null;
        }

        return $status;
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
