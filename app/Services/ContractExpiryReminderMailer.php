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

        if ($now->isMonday() || $now->isWednesday()) {
            $sent['three_month'] = $this->sendTier(ContractExpiryReminderTier::ThreeMonth, $now);
        }

        return $sent;
    }

    public function sendTier(ContractExpiryReminderTier $tier, Carbon $now): int
    {
        $today = $now->timezone('Asia/Kuala_Lumpur')->toDateString();
        $contracts = $this->contractsDueForTier($tier, $today);

        if ($contracts->isEmpty()) {
            return 0;
        }

        $sent = 0;

        foreach ($contracts->groupBy(fn (RentalContract $contract): string => (string) ($contract->premise?->negeri ?? '')) as $group) {
            $recipients = $this->negeriRecipients($group);

            if ($recipients === []) {
                continue;
            }

            $cc = $tier === ContractExpiryReminderTier::ThreeMonth
                ? $this->warningCcRecipients($recipients)
                : [];

            try {
                $this->deliver($recipients, $cc, $group, $tier);
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
                $tier === ContractExpiryReminderTier::ThreeMonth,
                fn ($query) => $query->with('followUpApplications'),
            )
            ->get()
            ->filter(fn (RentalContract $contract): bool => match ($tier) {
                ContractExpiryReminderTier::EightMonth => $contract->isDueForEightMonthReminder(),
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
            return [trim((string) $testRecipient)];
        }

        $negeri = $contracts->first()?->premise?->negeri;

        if (! filled($negeri)) {
            return [];
        }

        return User::query()
            ->where('role', 'admin_negeri')
            ->where('negeri', $negeri)
            ->where('is_active', true)
            ->whereNotNull('email')
            ->pluck('email')
            ->map(fn (mixed $email): string => trim((string) $email))
            ->filter(fn (string $email): bool => $email !== '')
            ->unique(fn (string $email): string => strtolower($email))
            ->values()
            ->all();
    }

    /**
     * @param  list<string>  $to
     * @return list<string>
     */
    public function warningCcRecipients(array $to): array
    {
        if (filled(config('mail.test_recipient'))) {
            return [];
        }

        $toLookup = collect($to)
            ->map(fn (string $email): string => strtolower($email))
            ->all();

        $hqEmails = User::query()
            ->where('role', 'admin_hq')
            ->where('is_active', true)
            ->whereNotNull('email')
            ->pluck('email');

        $seen = [];
        $cc = [];

        $positionEmails = EmelJawatan::query()->orderBy('susunan')->pluck('email');

        foreach ($positionEmails->merge($hqEmails) as $email) {
            $email = trim((string) $email);
            $key = strtolower($email);

            if ($email === '' || isset($seen[$key]) || in_array($key, $toLookup, true)) {
                continue;
            }

            $seen[$key] = true;
            $cc[] = $email;
        }

        return $cc;
    }

    /**
     * @param  list<string>  $to
     * @param  list<string>  $cc
     * @param  Collection<int, RentalContract>  $contracts
     */
    private function deliver(array $to, array $cc, Collection $contracts, ContractExpiryReminderTier $tier): void
    {
        $pending = Mail::to($to);

        if ($cc !== []) {
            $pending->cc($cc);
        }

        $pending->send(new ContractExpiryReminderMail($contracts->values(), $tier));
    }
}
