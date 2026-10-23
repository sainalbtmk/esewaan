<?php

namespace App\Console\Commands;

use App\Services\ContractExpiryReminderMailer;
use Illuminate\Console\Command;

class SendContractExpiryReminders extends Command
{
    protected $signature = 'contracts:send-expiry-reminders';

    protected $description = 'Hantar peringatan kontrak bawah 8 bulan, peringatan tegas bawah 5 bulan, dan amaran baki 3 bulan';

    public function handle(ContractExpiryReminderMailer $mailer): int
    {
        $sent = $mailer->sendDueReminders();

        $this->info(sprintf(
            'Peringatan bawah 8 bulan: %d kontrak. Peringatan tegas bawah 5 bulan: %d kontrak. Amaran baki 3 bulan: %d kontrak.',
            $sent['eight_month'],
            $sent['five_month'],
            $sent['three_month'],
        ));

        return self::SUCCESS;
    }
}
