<?php

use App\Mail\ContractExpiryReminderMail;
use App\Models\ContractExpiryReminderSend;
use App\Models\EmelJawatan;
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
    Carbon::setTestNow('2026-10-19 02:00:00');
});

afterEach(function () {
    Carbon::setTestNow();
});

function createExpiryReminderContract(User $negeriAdmin, int $daysRemaining): RentalContract
{
    $premise = Premise::query()->create([
        'nama_ptj' => 'Premis Peringatan '.$daysRemaining,
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

function createExpiryReminderFollowUp(RentalContract $parent, User $negeriAdmin, string $workflow): RentalContract
{
    return RentalContract::query()->create([
        'premise_id' => $parent->premise_id,
        'parent_contract_id' => $parent->id,
        'admin_negeri_user_id' => $negeriAdmin->id,
        'tarikh_mula' => RentalContract::PLACEHOLDER_CONTRACT_DATE,
        'tarikh_tamat' => RentalContract::PLACEHOLDER_CONTRACT_DATE,
        'kadar_sewa_bulanan' => 1000,
        'status_aktif' => 'dalam_proses',
        'workflow_tahap' => $workflow,
        'kategori_permohonan' => ApplicationCategories::LANJUTAN,
    ]);
}

test('reminder windows follow the 8, 5, and 3 month boundaries', function () {
    $negeri = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
    ]);

    expect(createExpiryReminderContract($negeri, 239)->isDueForEightMonthReminder())->toBeTrue()
        ->and(createExpiryReminderContract($negeri, 240)->isDueForEightMonthReminder())->toBeFalse()
        ->and(createExpiryReminderContract($negeri, 151)->isDueForEightMonthReminder())->toBeTrue()
        ->and(createExpiryReminderContract($negeri, 150)->isDueForEightMonthReminder())->toBeFalse()
        ->and(createExpiryReminderContract($negeri, 150)->isDueForFiveMonthFirmReminder())->toBeTrue()
        ->and(createExpiryReminderContract($negeri, 91)->isDueForFiveMonthFirmReminder())->toBeTrue()
        ->and(createExpiryReminderContract($negeri, 90)->isDueForFiveMonthFirmReminder())->toBeFalse()
        ->and(createExpiryReminderContract($negeri, 90)->isDueForThreeMonthWarning())->toBeTrue()
        ->and(createExpiryReminderContract($negeri, 0)->isDueForThreeMonthWarning())->toBeTrue()
        ->and(createExpiryReminderContract($negeri, 45)->isDueForThreeMonthWarning())->toBeTrue();
});

test('eight month reminder emails every negeri admin of the contract for three days then stops', function () {
    Mail::fake();

    $firstNegeri = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'negeri-satu@example.com',
    ]);
    $secondNegeri = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'negeri-dua@example.com',
    ]);
    $inactiveNegeri = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'negeri-nyahaktif@example.com',
        'is_active' => false,
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

    createExpiryReminderContract($firstNegeri, 200);

    foreach ([0, 1, 2] as $day) {
        Carbon::setTestNow(Carbon::parse('2026-10-19 02:00:00')->addDays($day));
        Mail::fake();

        Artisan::call('contracts:send-expiry-reminders');

        Mail::assertSent(ContractExpiryReminderMail::class, 1);
        Mail::assertSent(ContractExpiryReminderMail::class, function (ContractExpiryReminderMail $mail) use ($firstNegeri, $secondNegeri, $inactiveNegeri, $otherNegeri, $hq): bool {
            return $mail->tier === ContractExpiryReminderTier::EightMonth
                && $mail->hasTo($firstNegeri->email)
                && $mail->hasTo($secondNegeri->email)
                && ! $mail->hasTo($inactiveNegeri->email)
                && ! $mail->hasTo($otherNegeri->email)
                && ! $mail->hasTo($hq->email)
                && $mail->contracts->count() === 1
                && ! $mail->hasCc('ruslin@aadk.gov.my');
        });
    }

    Carbon::setTestNow(Carbon::parse('2026-10-19 02:00:00')->addDays(3));
    Mail::fake();

    Artisan::call('contracts:send-expiry-reminders');

    Mail::assertNothingSent();
    expect(ContractExpiryReminderSend::query()->where('tier', ContractExpiryReminderTier::EightMonth)->count())->toBe(3);
});

test('eight month reminder is sent only once on the same day', function () {
    Mail::fake();

    $negeri = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'negeri-harian@example.com',
    ]);

    createExpiryReminderContract($negeri, 180);

    Artisan::call('contracts:send-expiry-reminders');
    Artisan::call('contracts:send-expiry-reminders');

    Mail::assertSent(ContractExpiryReminderMail::class, 1);
    expect(ContractExpiryReminderSend::query()->count())->toBe(1);
});

test('eight month reminder stops once the contract reaches five months', function () {
    Mail::fake();

    $negeri = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'negeri-lima@example.com',
    ]);

    createExpiryReminderContract($negeri, 150);

    Artisan::call('contracts:send-expiry-reminders');

    Mail::assertSent(ContractExpiryReminderMail::class, function (ContractExpiryReminderMail $mail): bool {
        return $mail->tier === ContractExpiryReminderTier::FiveMonth;
    });
    Mail::assertNotSent(ContractExpiryReminderMail::class, function (ContractExpiryReminderMail $mail): bool {
        return $mail->tier === ContractExpiryReminderTier::EightMonth;
    });
});

test('firm reminder emails negeri admins every monday between five and three months', function () {
    Mail::fake();

    $negeri = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'negeri-tegas@example.com',
    ]);
    $other = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'negeri-tegas-dua@example.com',
    ]);

    createExpiryReminderContract($negeri, 120);
    createExpiryReminderContract($negeri, 91);

    Artisan::call('contracts:send-expiry-reminders');

    Mail::assertSent(ContractExpiryReminderMail::class, function (ContractExpiryReminderMail $mail) use ($negeri, $other): bool {
        return $mail->tier === ContractExpiryReminderTier::FiveMonth
            && $mail->hasTo($negeri->email)
            && $mail->hasTo($other->email)
            && $mail->contracts->count() === 2
            && ! $mail->hasCc('ruslin@aadk.gov.my')
            && $mail->envelope()->subject === 'SPEKTRA: Peringatan Tegas Kontrak Bawah 5 Bulan';
    });
});

test('firm reminder is not sent on other weekdays', function () {
    Mail::fake();
    Carbon::setTestNow('2026-10-20 02:00:00');

    $negeri = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'negeri-selasa@example.com',
    ]);

    createExpiryReminderContract($negeri, 120);

    Artisan::call('contracts:send-expiry-reminders');

    Mail::assertNothingSent();
});

test('three month warning emails the involved negeri and the jawatan list every day', function (string $date) {
    Mail::fake();
    Carbon::setTestNow($date);

    $negeri = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'negeri-amaran@example.com',
    ]);
    $colleague = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'negeri-amaran-dua@example.com',
    ]);
    $hq = User::factory()->create([
        'role' => 'admin_hq',
        'email' => 'hq-amaran@example.com',
    ]);
    $otherNegeri = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Melaka',
        'email' => 'melaka-amaran@example.com',
    ]);

    $contract = createExpiryReminderContract($negeri, 45);

    Artisan::call('contracts:send-expiry-reminders');

    Mail::assertSent(ContractExpiryReminderMail::class, function (ContractExpiryReminderMail $mail) use ($negeri, $colleague, $hq, $otherNegeri, $contract): bool {
        $html = $mail->render();

        return $mail->audience === ContractExpiryReminderMail::AUDIENCE_AMARAN
            && $mail->tier === ContractExpiryReminderTier::ThreeMonth
            && str_contains($html, 'AMARAN: Senarai Ruang Pejabat Hampir Tamat Tempoh dan Perlu Tindakan SEGERA')
            && str_contains($html, 'negeri yang terlibat sahaja')
            && str_contains($html, 'Premis Peringatan 45')
            && str_contains($html, 'Johor')
            && str_contains($html, 'BAHARU')
            && str_contains($html, $contract->contractRemainderLabel())
            && str_contains($html, 'Tarikh Mula Kontrak')
            && str_contains($html, 'Baki Bulan/Hari')
            && $mail->hasTo($negeri->email)
            && $mail->hasTo($colleague->email)
            && ! $mail->hasTo($hq->email)
            && ! $mail->hasTo($otherNegeri->email)
            && ! $mail->hasCc($hq->email)
            && ! $mail->hasCc('ruslin@aadk.gov.my')
            && $mail->contracts->count() === 1
            && $mail->envelope()->subject === 'SPEKTRA: AMARAN Senarai Ruang Pejabat Hampir Tamat Tempoh dan Perlu Tindakan SEGERA';
    });

    Mail::assertSent(ContractExpiryReminderMail::class, function (ContractExpiryReminderMail $mail) use ($negeri, $hq): bool {
        $html = $mail->render();
        $reachesJawatan = EmelJawatan::query()->orderBy('susunan')->pluck('email')
            ->every(fn (string $email): bool => $mail->hasTo($email));

        return $mail->audience === ContractExpiryReminderMail::AUDIENCE_SEGERA
            && str_contains($html, 'Senarai Ruang Pejabat Hampir Tamat Tempoh dan Perlu Tindakan SEGERA')
            && str_contains($html, 'tindakan SEGERA.')
            && ! str_contains($html, 'sama ada Pindah atau Lanjutan')
            && str_contains($html, 'Premis Peringatan 45')
            && $reachesJawatan
            && ! $mail->hasTo($negeri->email)
            && ! $mail->hasTo($hq->email)
            && ! $mail->hasCc($hq->email)
            && $mail->envelope()->subject === 'SPEKTRA: Senarai Ruang Pejabat Hampir Tamat Tempoh dan Perlu Tindakan SEGERA';
    });
})->with([
    'isnin' => '2026-10-19 02:00:00',
    'selasa' => '2026-10-20 02:00:00',
    'rabu' => '2026-10-21 02:00:00',
]);

test('three month warning continues after a follow-up is started and stops after ya hantar', function () {
    Mail::fake();
    Carbon::setTestNow('2026-10-21 02:00:00');

    $negeri = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'negeri-hantar@example.com',
    ]);

    $contract = createExpiryReminderContract($negeri, 30);
    $followUp = createExpiryReminderFollowUp($contract, $negeri, RentalContract::WORKFLOW_MENUNGGU_PROCEED_NEGERI);

    Artisan::call('contracts:send-expiry-reminders');

    Mail::assertSent(ContractExpiryReminderMail::class, function (ContractExpiryReminderMail $mail): bool {
        return $mail->tier === ContractExpiryReminderTier::ThreeMonth;
    });

    $followUp->update([
        'workflow_tahap' => RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ,
    ]);

    Carbon::setTestNow('2026-10-26 02:00:00');
    Mail::fake();

    Artisan::call('contracts:send-expiry-reminders');

    Mail::assertNothingSent();
});

test('three month warning resumes when a submitted follow-up is deleted', function () {
    Mail::fake();
    Carbon::setTestNow('2026-10-21 02:00:00');

    $negeri = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'negeri-padam@example.com',
    ]);

    $contract = createExpiryReminderContract($negeri, 20);
    $followUp = createExpiryReminderFollowUp($contract, $negeri, RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ);
    $followUp->delete();

    Artisan::call('contracts:send-expiry-reminders');

    Mail::assertSent(ContractExpiryReminderMail::class, function (ContractExpiryReminderMail $mail): bool {
        return $mail->tier === ContractExpiryReminderTier::ThreeMonth;
    });
});

test('warning copy stays on the test recipient when mail test override is set', function () {
    Mail::fake();
    Carbon::setTestNow('2026-10-21 02:00:00');
    config(['mail.test_recipient' => 'qa@example.com']);

    $negeri = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'negeri-sebenar@example.com',
    ]);
    $hq = User::factory()->create([
        'role' => 'admin_hq',
        'email' => 'hq-sebenar@example.com',
    ]);

    createExpiryReminderContract($negeri, 40);

    Artisan::call('contracts:send-expiry-reminders');

    Mail::assertSent(ContractExpiryReminderMail::class, function (ContractExpiryReminderMail $mail) use ($negeri, $hq): bool {
        return $mail->hasTo('qa@example.com')
            && ! $mail->hasTo($negeri->email)
            && ! $mail->hasTo($hq->email)
            && ! $mail->hasCc($hq->email)
            && ! $mail->hasTo('ruslin@aadk.gov.my');
    });

    Mail::assertSent(ContractExpiryReminderMail::class, 2);
});

test('contracts outside the reminder windows are skipped', function () {
    Mail::fake();

    $negeri = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'negeri-luar@example.com',
    ]);

    createExpiryReminderContract($negeri, 240);
    $expired = createExpiryReminderContract($negeri, 10);
    $expired->update([
        'sah_sehingga' => now()->subDay()->toDateString(),
    ]);
    $superseded = createExpiryReminderContract($negeri, 40);
    $superseded->update([
        'superseded_at' => now(),
    ]);

    Artisan::call('contracts:send-expiry-reminders');

    Mail::assertNothingSent();
});

test('reminders use the real agreement end date', function () {
    Mail::fake();

    $negeri = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'negeri-tarikh@example.com',
    ]);

    $premise = Premise::query()->create([
        'nama_ptj' => 'Premis Tarikh Sebenar',
        'negeri' => 'Johor',
        'daerah' => 'Johor Bahru',
        'alamat_penuh' => 'Alamat',
        'nama_pemilik' => 'Pemilik',
    ]);

    RentalContract::query()->create([
        'premise_id' => $premise->id,
        'admin_negeri_user_id' => $negeri->id,
        'tarikh_mula' => now()->subYear()->toDateString(),
        'tarikh_tamat' => now()->addDays(200)->toDateString(),
        'kadar_sewa_bulanan' => 1000,
        'status_aktif' => 'aktif',
        'workflow_tahap' => RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'kategori_permohonan' => ApplicationCategories::BARU,
        'hq_approved_at' => now()->subMonths(6),
    ]);

    Artisan::call('contracts:send-expiry-reminders');

    Mail::assertSent(ContractExpiryReminderMail::class, function (ContractExpiryReminderMail $mail) use ($negeri): bool {
        $html = $mail->render();
        $contract = $mail->contracts->first();

        return $mail->tier === ContractExpiryReminderTier::EightMonth
            && $mail->hasTo($negeri->email)
            && $contract?->premise?->nama_ptj === 'Premis Tarikh Sebenar'
            && str_contains($html, 'PERINGATAN PENGHANTARAN PERMOHONAN PELANJUTAN')
            && str_contains($html, 'https://spektra.aadk.gov.my/')
            && str_contains($html, 'https://promis.bph.gov.my/')
            && str_contains($html, 'Pelanjutan')
            && str_contains($html, 'Belum Terima')
            && str_contains($html, 'Premis Tarikh Sebenar')
            && str_contains($html, $contract->contractStartDate()?->format('d/m/Y') ?? '')
            && str_contains($html, $contract->contractEndDate()?->format('d/m/Y') ?? '');
    });
});

test('eight month reminder skips a contract whose extension was already sent to headquarters', function () {
    Mail::fake();

    $negeri = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'negeri-sudah-hantar@example.com',
    ]);

    $contract = createExpiryReminderContract($negeri, 200);
    createExpiryReminderFollowUp($contract, $negeri, RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ);

    Artisan::call('contracts:send-expiry-reminders');

    Mail::assertNothingSent();
});

test('eight month reminder still includes a contract whose extension has not been submitted', function () {
    Mail::fake();

    $negeri = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'negeri-belum-hantar@example.com',
    ]);

    $contract = createExpiryReminderContract($negeri, 200);
    createExpiryReminderFollowUp($contract, $negeri, RentalContract::WORKFLOW_MENUNGGU_PROCEED_NEGERI);

    Artisan::call('contracts:send-expiry-reminders');

    Mail::assertSent(ContractExpiryReminderMail::class, function (ContractExpiryReminderMail $mail) use ($negeri): bool {
        return $mail->tier === ContractExpiryReminderTier::EightMonth
            && $mail->hasTo($negeri->email)
            && str_contains($mail->render(), 'Belum Terima');
    });
});

test('expiry reminders are scheduled daily at 8am malaysia time', function () {
    $events = collect(app('Illuminate\Console\Scheduling\Schedule')->events());

    $reminder = $events->first(fn ($event) => str_contains($event->command ?? '', 'contracts:send-expiry-reminders'));

    expect($reminder)->not->toBeNull()
        ->and($reminder->expression)->toBe('0 8 * * *')
        ->and($reminder->timezone)->toBe('Asia/Kuala_Lumpur');

    $retired = $events->filter(fn ($event) => str_contains($event->command ?? '', 'contracts:send-expiry-notification')
        || str_contains($event->command ?? '', 'contracts:send-under-three-months-reminder'));

    expect($retired)->toBeEmpty();
});

test('warning copy recipients include the headquarters distribution list', function () {
    expect(EmelJawatan::query()->orderBy('susunan')->pluck('email')->all())->toBe([
        'ruslin@aadk.gov.my',
        'shobah@aadk.gov.my',
        'lai@aadk.gov.my',
        'zasmi@aadk.gov.my',
    ]);
});
