<?php

use App\Mail\ContractExpiryReminderMail;
use App\Models\Premise;
use App\Models\RentalContract;
use App\Models\User;
use App\Support\ApplicationCategories;
use App\Support\ContractExpiryReminderTier;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['mail.test_recipient' => null]);
});

afterEach(function () {
    Carbon::setTestNow();
});

function createUnderThreeMonthsContract(User $negeriAdmin, int $daysRemaining = 45): RentalContract
{
    $premise = Premise::query()->create([
        'nama_ptj' => 'Premis Bawah 3 Bulan',
        'negeri' => $negeriAdmin->negeri,
        'daerah' => $negeriAdmin->negeri,
        'alamat_penuh' => 'Alamat',
        'nama_pemilik' => 'Pemilik',
    ]);

    return RentalContract::query()->create([
        'premise_id' => $premise->id,
        'admin_negeri_user_id' => $negeriAdmin->id,
        'tarikh_mula' => RentalContract::PLACEHOLDER_CONTRACT_DATE,
        'tarikh_tamat' => RentalContract::PLACEHOLDER_CONTRACT_DATE,
        'kadar_sewa_bulanan' => 1000,
        'status_aktif' => 'aktif',
        'workflow_tahap' => RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'kategori_permohonan' => ApplicationCategories::BARU,
        'hq_approved_at' => now()->subMonths(6),
        'sah_sehingga' => now()->addDays($daysRemaining)->toDateString(),
    ]);
}

test('weekly reminder emails negeri admins for under three month contracts without follow-up', function () {
    Mail::fake();
    Carbon::setTestNow('2026-10-19 02:00:00');

    $negeri = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'negeri-johor@example.com',
    ]);
    $otherNegeri = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Melaka',
        'email' => 'negeri-melaka@example.com',
    ]);
    $hq = User::factory()->create([
        'role' => 'admin_hq',
        'email' => 'hq@example.com',
    ]);

    createUnderThreeMonthsContract($negeri, 40);
    createUnderThreeMonthsContract($otherNegeri, 20);

    Artisan::call('contracts:send-expiry-reminders');

    Mail::assertSent(ContractExpiryReminderMail::class, function (ContractExpiryReminderMail $mail) use ($negeri): bool {
        return $mail->tier === ContractExpiryReminderTier::ThreeMonth
            && $mail->hasTo($negeri->email)
            && $mail->contracts->count() === 1
            && $mail->contracts->first()?->premise?->negeri === 'Johor';
    });

    Mail::assertSent(ContractExpiryReminderMail::class, function (ContractExpiryReminderMail $mail) use ($otherNegeri): bool {
        return $mail->tier === ContractExpiryReminderTier::ThreeMonth
            && $mail->hasTo($otherNegeri->email)
            && $mail->contracts->count() === 1
            && $mail->contracts->first()?->premise?->negeri === 'Melaka';
    });

    Mail::assertNotSent(ContractExpiryReminderMail::class, function (ContractExpiryReminderMail $mail) use ($hq): bool {
        return $mail->hasTo($hq->email);
    });

    Mail::assertSent(ContractExpiryReminderMail::class, function (ContractExpiryReminderMail $mail) use ($hq): bool {
        return $mail->hasCc($hq->email);
    });
});

test('weekly reminder continues until the follow-up is sent to headquarters', function () {
    Mail::fake();
    Carbon::setTestNow('2026-10-19 02:00:00');

    $negeri = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'negeri-action@example.com',
    ]);

    $contract = createUnderThreeMonthsContract($negeri, 30);

    RentalContract::query()->create([
        'premise_id' => $contract->premise_id,
        'parent_contract_id' => $contract->id,
        'admin_negeri_user_id' => $negeri->id,
        'tarikh_mula' => RentalContract::PLACEHOLDER_CONTRACT_DATE,
        'tarikh_tamat' => RentalContract::PLACEHOLDER_CONTRACT_DATE,
        'kadar_sewa_bulanan' => 1000,
        'status_aktif' => 'dalam_proses',
        'workflow_tahap' => RentalContract::WORKFLOW_MENUNGGU_PROCEED_NEGERI,
        'kategori_permohonan' => ApplicationCategories::LANJUTAN,
    ]);

    Artisan::call('contracts:send-expiry-reminders');

    Mail::assertSent(ContractExpiryReminderMail::class, 1);
});

test('weekly reminder skips contracts once negeri confirms hantar ke ibu pejabat', function () {
    Mail::fake();
    Carbon::setTestNow('2026-10-21 02:00:00');

    $negeri = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'negeri-ya-hantar@example.com',
    ]);

    $contract = createUnderThreeMonthsContract($negeri, 30);

    RentalContract::query()->create([
        'premise_id' => $contract->premise_id,
        'parent_contract_id' => $contract->id,
        'admin_negeri_user_id' => $negeri->id,
        'tarikh_mula' => RentalContract::PLACEHOLDER_CONTRACT_DATE,
        'tarikh_tamat' => RentalContract::PLACEHOLDER_CONTRACT_DATE,
        'kadar_sewa_bulanan' => 1000,
        'status_aktif' => 'dalam_proses',
        'workflow_tahap' => RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ,
        'kategori_permohonan' => ApplicationCategories::LANJUTAN,
    ]);

    Artisan::call('contracts:send-expiry-reminders');

    Mail::assertNothingSent();
});

test('weekly reminder skips contracts with more than three months remaining', function () {
    Mail::fake();
    Carbon::setTestNow('2026-10-21 02:00:00');

    $negeri = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'negeri-safe@example.com',
    ]);

    createUnderThreeMonthsContract($negeri, 120);

    Artisan::call('contracts:send-expiry-reminders');

    Mail::assertNothingSent();
});

test('under three months reminder is scheduled every monday and wednesday through the daily reminder command', function () {
    $events = collect(app('Illuminate\Console\Scheduling\Schedule')->events())
        ->filter(fn ($event) => str_contains($event->command ?? '', 'contracts:send-expiry-reminders'));

    expect($events)->not->toBeEmpty();

    $event = $events->first();

    expect($event->expression)->toBe('0 9 * * *')
        ->and($event->timezone)->toBe('Asia/Kuala_Lumpur');
});
