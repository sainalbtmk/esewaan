<?php

use App\Models\ActivityLog;
use App\Models\Premise;
use App\Models\RentalContract;
use App\Models\User;
use App\Support\AdminProceedSteps;
use App\Support\ApplicationCategories;
use App\Support\HqJrpChecklist;
use App\Support\Totp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

function mutationLogContract(string $negeri, string $workflow, string $nama = 'Premis Mutasi'): RentalContract
{
    $premise = Premise::query()->create([
        'nama_ptj' => $nama,
        'negeri' => $negeri,
        'daerah' => $negeri,
        'alamat_penuh' => 'Alamat asal',
        'jenis_bangunan' => 'kompleks kerajaan',
        'nama_pemilik' => 'Pemilik Asal',
    ]);

    return RentalContract::query()->create([
        'premise_id' => $premise->id,
        'tarikh_mula' => RentalContract::PLACEHOLDER_CONTRACT_DATE,
        'tarikh_tamat' => RentalContract::PLACEHOLDER_CONTRACT_DATE,
        'kadar_sewa_bulanan' => 0,
        'status_aktif' => 'dalam_proses',
        'workflow_tahap' => $workflow,
        'kategori_permohonan' => ApplicationCategories::BARU,
    ]);
}

test('application autosave records an update and skips an unchanged repeat', function () {
    $admin = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Johor']);
    $contract = mutationLogContract('Johor', RentalContract::WORKFLOW_MENUNGGU_PROCEED_NEGERI);
    $payload = [
        'negeri' => 'Johor',
        'nama_ptj' => 'Premis Dikemaskini',
        'alamat_penuh' => 'Alamat baharu',
        'nama_pemilik' => 'Pemilik Baharu',
    ];

    $this->actingAs($admin)
        ->patchJson(route('application.autosave', $contract), $payload)
        ->assertSuccessful();

    $log = ActivityLog::query()->where('action', 'application_updated')->first();

    expect($log)->not->toBeNull()
        ->and($log->description)->toContain('Draf permohonan premis Premis Dikemaskini dikemaskini')
        ->and($log->user_id)->toBe($admin->id);

    $this->actingAs($admin)
        ->patchJson(route('application.autosave', $contract), $payload)
        ->assertSuccessful();

    expect(ActivityLog::query()->where('action', 'application_updated')->count())->toBe(1);
});

test('an unfinished proceed step edit is recorded as a draft update', function () {
    $admin = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Johor']);
    $contract = mutationLogContract('Johor', RentalContract::WORKFLOW_MENUNGGU_PROCEED_NEGERI);
    $key = AdminProceedSteps::keyForStep(2);
    $payload = [
        'current_step' => 2,
        'proceed_steps' => [
            $key => [
                'notes' => 'Catatan draf langkah',
            ],
        ],
    ];

    $this->actingAs($admin)
        ->put(route('admin-proceed.update', $contract), $payload)
        ->assertRedirect();

    expect(AdminProceedSteps::isStepCompleted($contract->fresh(), 2))->toBeFalse()
        ->and(ActivityLog::query()->where('action', 'admin_proceed_draft_saved')->count())->toBe(1)
        ->and(ActivityLog::query()->where('action', 'admin_proceed_step_completed')->count())->toBe(0);

    $this->actingAs($admin)
        ->put(route('admin-proceed.update', $contract), $payload)
        ->assertRedirect();

    expect(ActivityLog::query()->where('action', 'admin_proceed_draft_saved')->count())->toBe(1);
});

test('checklist and draft acknowledgement edits are recorded once per change', function () {
    $hq = User::factory()->create(['role' => 'admin_hq', 'name' => 'Pentadbir Ibu Pejabat']);
    $review = mutationLogContract('Melaka', RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ, 'Premis Checklist');
    $checklist = [
        'jrp_checklist' => [
            HqJrpChecklist::KP => '1',
            HqJrpChecklist::MOF => '0',
        ],
    ];

    $this->actingAs($hq)
        ->patchJson(route('status-permohonan.checklist-autosave', $review), $checklist)
        ->assertSuccessful();

    $this->actingAs($hq)
        ->patchJson(route('status-permohonan.checklist-autosave', $review), $checklist)
        ->assertSuccessful();

    expect(ActivityLog::query()->where('action', 'checklist_updated')->count())->toBe(1)
        ->and(ActivityLog::query()->where('action', 'checklist_updated')->value('description'))
        ->toContain('Premis Checklist');

    $negeri = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Johor']);
    $draft = mutationLogContract('Johor', RentalContract::WORKFLOW_DRAF_PERJANJIAN_LULUS, 'Premis Pengesahan');
    $acknowledgements = [
        'acknowledgements' => [
            'draf_akhir_diterima_acknowledged' => '1',
        ],
    ];

    $this->actingAs($negeri)
        ->patchJson(route('status-permohonan.negeri-acknowledgements-autosave', $draft), $acknowledgements)
        ->assertSuccessful();

    $this->actingAs($negeri)
        ->patchJson(route('status-permohonan.negeri-acknowledgements-autosave', $draft), $acknowledgements)
        ->assertSuccessful();

    expect(ActivityLog::query()->where('action', 'draft_acknowledgement_updated')->count())->toBe(1)
        ->and(ActivityLog::query()->where('action', 'draft_acknowledgement_updated')->value('description'))
        ->toContain('Premis Pengesahan');
});

test('marking notifications as read is recorded', function () {
    $admin = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Johor']);
    mutationLogContract('Johor', RentalContract::WORKFLOW_MENUNGGU_PROCEED_NEGERI, 'Premis Notifikasi');

    $this->actingAs($admin)
        ->post(route('notifications.mark-all-read'))
        ->assertRedirect(route('notifications.index'));

    expect(ActivityLog::query()->where('action', 'notifications_marked_read')->count())->toBe(1);
});

test('starting two factor setup and using a recovery code are recorded', function () {
    config(['auth.mfa.required' => true]);

    $user = User::factory()->create([
        'email' => 'mutasi-mfa@esewa.test',
        'password' => Hash::make('password'),
        'role' => 'admin_hq',
    ]);

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('two-factor.setup'));

    $this->get(route('two-factor.setup'))->assertSuccessful();
    $this->get(route('two-factor.setup'))->assertSuccessful();

    expect(ActivityLog::query()->where('action', 'two_factor_setup_started')->count())->toBe(1);

    $secret = (string) $user->fresh()->two_factor_secret;
    $recoveryCodes = session('two_factor.recovery_plain');

    $this->post(route('two-factor.confirm'), [
        'code' => Totp::code($secret),
    ])->assertRedirect(route('two-factor.recovery'));

    $this->post(route('logout'));

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('two-factor.login'));

    $this->post(route('two-factor.login.store'), [
        'code' => $recoveryCodes[0],
    ])->assertRedirect(route('dashboard'));

    expect(ActivityLog::query()->where('action', 'recovery_code_used')->count())->toBe(1)
        ->and(ActivityLog::query()->where('action', 'two_factor_confirmed')->count())->toBe(1)
        ->and(ActivityLog::query()->where('action', 'login')->count())->toBe(2);
});
