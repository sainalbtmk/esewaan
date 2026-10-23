<?php

use App\Models\Premise;
use App\Models\RentalContract;
use App\Models\User;
use App\Support\MalaysianStates;
use App\Support\PremiseStatuses;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('seeder creates one active negeri admin for every state', function () {
    $this->seed(DatabaseSeeder::class);

    expect(MalaysianStates::values())->toContain('W.P. Kuala Lumpur', 'W.P. Labuan', 'W.P. Putrajaya');

    foreach (MalaysianStates::values() as $negeri) {
        $user = User::query()->where('email', UserSeeder::negeriAdminEmail($negeri))->first();

        expect($user)->not->toBeNull()
            ->and($user->name)->toBe('Pentadbir '.$negeri)
            ->and($user->role)->toBe('admin_negeri')
            ->and($user->negeri)->toBe($negeri)
            ->and($user->is_active)->toBeTrue()
            ->and(Hash::check('password', $user->password))->toBeTrue();
    }

    $this->post(route('login.store'), [
        'email' => UserSeeder::negeriAdminEmail('Johor'),
        'password' => 'password',
    ])->assertRedirect(route('dashboard'));
});

test('seeder creates five rental premises per state across remaining month buckets', function () {
    $this->seed(DatabaseSeeder::class);

    expect(RentalContract::query()->where('no_fail_rujukan', 'like', 'DUMMY/%')->count())
        ->toBe(count(MalaysianStates::values()) * 5);

    foreach (MalaysianStates::values() as $negeri) {
        $contracts = RentalContract::query()
            ->with('premise')
            ->where('no_fail_rujukan', 'like', 'DUMMY/%')
            ->whereHas('premise', fn ($query) => $query->where('negeri', $negeri))
            ->get()
            ->keyBy(fn (RentalContract $contract): int => (int) $contract->baki_bulan);

        expect($contracts->keys()->sort()->values()->all())->toBe([2, 4, 6, 12, 18]);

        expect($contracts[2]->isDueForThreeMonthWarning())->toBeTrue()
            ->and($contracts[2]->premise?->nama_ptj)->toContain('Kurang 3 bulan')
            ->and($contracts[4]->isDueForFiveMonthFirmReminder())->toBeTrue()
            ->and($contracts[4]->premise?->nama_ptj)->toContain('Kurang 5 bulan')
            ->and($contracts[6]->isDueForEightMonthReminder())->toBeTrue()
            ->and($contracts[6]->premise?->nama_ptj)->toContain('Kurang 8 bulan')
            ->and($contracts[12]->isContractEndWithinEightMonths())->toBeFalse()
            ->and($contracts[12]->premise?->nama_ptj)->toContain('Lebih 8 bulan')
            ->and($contracts[18]->isContractEndWithinEightMonths())->toBeFalse()
            ->and($contracts[18]->premise?->nama_ptj)->toContain('Lebih 8 bulan');

        foreach ($contracts as $contract) {
            expect($contract->isHqApproved())->toBeTrue()
                ->and($contract->status_aktif)->toBe('aktif')
                ->and($contract->premise?->status_premis)->toBe(PremiseStatuses::RENTED)
                ->and($contract->premise?->baki_bulan)->toBe($contract->baki_bulan)
                ->and($contract->adminNegeriUser?->negeri)->toBe($negeri);
        }
    }

    $johor = User::query()->where('email', UserSeeder::negeriAdminEmail('Johor'))->firstOrFail();

    $this->actingAs($johor)
        ->get(route('dashboard'))
        ->assertSuccessful()
        ->assertViewHas('kontrakAktif', 5)
        ->assertViewHas('kontrakTigaBulan', 1)
        ->assertViewHas('kontrakLapanBulan', 2)
        ->assertViewHas('kontrakLebihLapanBulan', 2);
});

test('running the rental premise seeder again does not duplicate records', function () {
    $this->seed(DatabaseSeeder::class);
    $this->seed(DatabaseSeeder::class);

    expect(User::query()->where('email', UserSeeder::negeriAdminEmail('Putrajaya'))->count())->toBe(1)
        ->and(Premise::query()->where('negeri', 'W.P. Putrajaya')->count())->toBe(5)
        ->and(RentalContract::query()->where('no_fail_rujukan', 'like', 'DUMMY/%')->count())
        ->toBe(count(MalaysianStates::values()) * 5);
});
