<?php

namespace App\Services;

use App\Mail\ContractExpiryReminderMail;
use App\Models\ContractExpiryReminderSend;
use App\Models\EmelJawatan;
use App\Models\RentalContract;
use App\Models\User;
use App\Support\ContractExpiryReminderTier;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ContractExpiryReminderMailer
{
    /**
     * @return array{eight_month: int, five_month: int, three_month: int}
     */
    public function sendDueReminders(?Carbon $now = null): array
    {
        $now = ($now ?? Carbon::now('Asia/Kuala_Lumpur'))->timezone('Asia/Kuala_Lumpur');

        $sent = [
            'eight_month' => $this->sendTier(ContractExpiryReminderTier::EightMonth, $now),
            'five_month' => 0,
            'three_month' => 0,
        ];

        if ($now->isMonday()) {
            $sent['five_month'] = $this->sendTier(ContractExpiryReminderTier::FiveMonth, $now);
        }

        $sent['three_month'] = $this->sendTier(ContractExpiryReminderTier::ThreeMonth, $now);

        return $sent;
    }

    public function sendTier(ContractExpiryReminderTier $tier, Carbon $now): int
    {
        $today = $now->timezone('Asia/Kuala_Lumpur')->toDateString();
        $contracts = $this->contractsDueForTier($tier, $today);

        if ($contracts->isEmpty()) {
            return 0;
        }

        if ($tier === ContractExpiryReminderTier::ThreeMonth) {
            return $this->sendThreeMonthWarnings($contracts, $today);
        }

        $sent = 0;

        foreach ($contracts->groupBy(fn (RentalContract $contract): string => (string) ($contract->premise?->negeri ?? '')) as $group) {
            $recipients = $this->negeriRecipients($group);

            if ($recipients === []) {
                continue;
            }

            try {
                $this->deliver($recipients, [], $group, $tier);
            } catch (Throwable $exception) {
                report($exception);

                continue;
            }

            foreach ($group as $contract) {
                ContractExpiryReminderSend::query()->firstOrCreate([
                    'rental_contract_id' => $contract->id,
                    'tier' => $tier,
                    'sent_on' => $today,
                ]);
                $sent++;
            }
        }

        return $sent;
    }

    /**
     * @param  Collection<int, RentalContract>  $contracts
     */
    private function sendThreeMonthWarnings(Collection $contracts, string $today): int
    {
        $notified = [];

        $positionRecipients = $this->positionWarningRecipients();

        if ($positionRecipients !== []) {
            try {
                $this->deliver(
                    $positionRecipients,
                    [],
                    $contracts,
                    ContractExpiryReminderTier::ThreeMonth,
                    ContractExpiryReminderMail::AUDIENCE_SEGERA,
                );

                foreach ($contracts as $contract) {
                    $notified[$contract->id] = $contract;
                }
            } catch (Throwable $exception) {
                report($exception);
            }
        }

        foreach ($contracts->groupBy(fn (RentalContract $contract): string => (string) ($contract->premise?->negeri ?? '')) as $group) {
            $recipients = $this->negeriRecipients($group);

            if ($recipients === []) {
                continue;
            }

            try {
                $this->deliver(
                    $recipients,
                    [],
                    $group,
                    ContractExpiryReminderTier::ThreeMonth,
                    ContractExpiryReminderMail::AUDIENCE_AMARAN,
                );
            } catch (Throwable $exception) {
                report($exception);

                continue;
            }

            foreach ($group as $contract) {
                $notified[$contract->id] = $contract;
            }
        }

        foreach ($notified as $contract) {
            ContractExpiryReminderSend::query()->firstOrCreate([
                'rental_contract_id' => $contract->id,
                'tier' => ContractExpiryReminderTier::ThreeMonth,
                'sent_on' => $today,
            ]);
        }

        return count($notified);
    }

    /**
     * @return Collection<int, RentalContract>
     */
    public function contractsDueForTier(ContractExpiryReminderTier $tier, string $today): Collection
    {
        $contracts = $this->contractsInTier($tier);

        if ($contracts->isEmpty()) {
            return $contracts;
        }

        $sends = ContractExpiryReminderSend::query()
            ->whereIn('rental_contract_id', $contracts->pluck('id'))
            ->where('tier', $tier)
            ->get()
            ->groupBy('rental_contract_id');

        return $contracts
            ->filter(function (RentalContract $contract) use ($sends, $tier, $today): bool {
                $history = $sends->get($contract->id, collect());

                $alreadySentToday = $history->contains(
                    fn (ContractExpiryReminderSend $send): bool => $send->sent_on?->toDateString() === $today
                );

                if ($alreadySentToday) {
                    return false;
                }

                $maximum = $tier->maximumSends();

                return $maximum === null || $history->count() < $maximum;
            })
            ->values();
    }

    /**
     * @return Collection<int, RentalContract>
     */
    public function contractsInTier(ContractExpiryReminderTier $tier): Collection
    {
        return RentalContract::query()
            ->hqApproved()
            ->notSuperseded()
            ->kontrakNotExpired()
            ->with(['premise'])
            ->when(
                $tier !== ContractExpiryReminderTier::FiveMonth,
                fn ($query) => $query->with('followUpApplications'),
            )
            ->get()
            ->filter(fn (RentalContract $contract): bool => match ($tier) {
                ContractExpiryReminderTier::EightMonth => $contract->isDueForEightMonthReminder()
                    && ! $contract->followUpHasBeenSentToHq(),
                ContractExpiryReminderTier::FiveMonth => $contract->isDueForFiveMonthFirmReminder(),
                ContractExpiryReminderTier::ThreeMonth => $contract->isDueForThreeMonthWarning()
                    && ! $contract->followUpHasBeenSentToHq(),
            })
            ->sortBy(fn (RentalContract $contract) => $contract->daysUntilContractEnd())
            ->values();
    }

    /**
     * @param  Collection<int, RentalContract>  $contracts
     * @return list<string>
     */
    public function negeriRecipients(Collection $contracts): array
    {
        $testRecipient = config('mail.test_recipient');

        if (filled($testRecipient)) {
            return $this->usableEmails([(string) $testRecipient]);
        }

        $negeri = $contracts->first()?->premise?->negeri;

        if (! filled($negeri)) {
            return [];
        }

        return $this->usableEmails(User::query()
            ->where('role', 'admin_negeri')
            ->where('negeri', $negeri)
            ->where('is_active', true)
            ->whereNotNull('email')
            ->pluck('email')
            ->all());
    }

    /**
     * @return list<string>
     */
    public function positionWarningRecipients(): array
    {
        $testRecipient = config('mail.test_recipient');

        if (filled($testRecipient)) {
            return $this->usableEmails([(string) $testRecipient]);
        }

        return $this->usableEmails(
            EmelJawatan::query()->orderBy('susunan')->pluck('email')->all(),
        );
    }

    /**
     * @param  list<mixed>  $emails
     * @return list<string>
     */
    private function usableEmails(array $emails): array
    {
        $usable = [];
        $seen = [];

        foreach ($emails as $email) {
            $email = trim((string) $email);
            $key = strtolower($email);

            if ($email === '' || isset($seen[$key])) {
                continue;
            }

            $seen[$key] = true;

            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                report(new \RuntimeException('Penerima peringatan kontrak tidak sah.'));

                continue;
            }

            $usable[] = $email;
        }

        return $usable;
    }

    /**
     * @param  list<string>  $to
     * @param  list<string>  $cc
     * @param  Collection<int, RentalContract>  $contracts
     */
    private function deliver(
        array $to,
        array $cc,
        Collection $contracts,
        ContractExpiryReminderTier $tier,
        string $audience = ContractExpiryReminderMail::AUDIENCE_STANDARD,
    ): void {
        $pending = Mail::to($to);

        if ($cc !== []) {
            $pending->cc($cc);
        }

        $pending->send(new ContractExpiryReminderMail($contracts->values(), $tier, $audience));
    }
}
