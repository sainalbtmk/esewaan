<?php

use App\Models\Premise;
use App\Models\RentalContract;
use App\Models\User;
use App\Support\ApplicationCategories;
use App\Support\HqJrpChecklist;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createHqReviewContractForAutosave(string $negeri = 'Melaka'): RentalContract
{
    $premise = Premise::query()->create([
        'nama_ptj' => 'Premis Checklist Autosave',
        'negeri' => $negeri,
        'daerah' => $negeri,
        'alamat_penuh' => 'Alamat',
        'nama_pemilik' => 'Pemilik',
    ]);

    return RentalContract::query()->create([
        'premise_id' => $premise->id,
        'tarikh_mula' => RentalContract::PLACEHOLDER_CONTRACT_DATE,
        'tarikh_tamat' => RentalContract::PLACEHOLDER_CONTRACT_DATE,
        'kadar_sewa_bulanan' => 0,
        'status_aktif' => 'dalam_proses',
        'workflow_tahap' => RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ,
        'kategori_permohonan' => ApplicationCategories::BARU,
    ]);
}

test('admin hq can autosave jrp checklist without approving application', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);
    $contract = createHqReviewContractForAutosave();

    $this->actingAs($admin)
        ->patch(route('status-permohonan.checklist-autosave', $contract), [
            'jrp_checklist' => [
                HqJrpChecklist::KP => '1',
                HqJrpChecklist::MOF => '0',
                HqJrpChecklist::EPU => '0',
                HqJrpChecklist::AADK_RECEIVE_EPU_COMMENTS => '1',
                HqJrpChecklist::AADK_RECEIVE_MOF_COMMENTS => '0',
                HqJrpChecklist::AADK_SUBMIT_BPH => '0',
                HqJrpChecklist::AADK_RECEIVE_BPH_APPROVAL => '0',
                'dates' => [
                    HqJrpChecklist::AADK_RECEIVE_EPU_COMMENTS => '2026-07-16',
                ],
            ],
        ])
        ->assertSuccessful()
        ->assertJson([
            'success' => true,
            'message' => 'Checklist disimpan.',
        ]);

    $contract->refresh();

    expect($contract->isPendingHqReview())->toBeTrue()
        ->and($contract->hq_approved_at)->toBeNull()
        ->and($contract->hq_jrp_checklist[HqJrpChecklist::KP])->toBeTrue()
        ->and($contract->hq_jrp_checklist[HqJrpChecklist::AADK_RECEIVE_EPU_COMMENTS])->toBeTrue()
        ->and($contract->hq_jrp_checklist['dates'][HqJrpChecklist::AADK_RECEIVE_EPU_COMMENTS])->toBe('2026-07-16');
});

test('hq review page restores autosaved jrp checklist values', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);
    $contract = createHqReviewContractForAutosave();
    $contract->update([
        'hq_jrp_checklist' => [
            HqJrpChecklist::KP => true,
            HqJrpChecklist::MOF => false,
            HqJrpChecklist::EPU => false,
            HqJrpChecklist::AADK_RECEIVE_EPU_COMMENTS => true,
            HqJrpChecklist::AADK_RECEIVE_MOF_COMMENTS => false,
            HqJrpChecklist::AADK_SUBMIT_BPH => false,
            HqJrpChecklist::AADK_RECEIVE_BPH_APPROVAL => false,
            'dates' => [
                HqJrpChecklist::AADK_RECEIVE_EPU_COMMENTS => '2026-07-16',
            ],
        ],
    ]);

    $html = $this->actingAs($admin)
        ->get(route('status-permohonan.review', $contract))
        ->assertSuccessful()
        ->assertSee('id="hq-jrp-checklist"', false)
        ->assertSee('checklist-autosave', false)
        ->getContent();

    preg_match('/<input\b[^>]*data-jrp-key="aadk_development_receive_epu_comments"[^>]*>/', $html, $checkbox);
    preg_match('/id="jrp_checklist_date_aadk_development_receive_epu_comments"[^>]*value="([^"]*)"/', $html, $date);

    expect($checkbox[0] ?? '')->toContain('checked')
        ->and($date[1] ?? '')->toBe('2026-07-16');
});

test('hq review page shows catatan only after mof and ke boxes are ticked', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);
    $contract = createHqReviewContractForAutosave();
    $contract->update([
        'keluasan_mp' => 100,
        'kategori_permohonan' => ApplicationCategories::LANJUTAN,
        'hq_jrp_checklist' => [
            HqJrpChecklist::KP => false,
            HqJrpChecklist::MOF => true,
            HqJrpChecklist::EPU => false,
            HqJrpChecklist::AADK_RECEIVE_EPU_COMMENTS => false,
            HqJrpChecklist::AADK_RECEIVE_MOF_COMMENTS => false,
            HqJrpChecklist::AADK_SUBMIT_BPH => false,
            HqJrpChecklist::AADK_RECEIVE_BPH_APPROVAL => false,
            'notes' => [
                HqJrpChecklist::MOF => 'Permohonan MOF sudah dihantar.',
            ],
        ],
    ]);

    $html = $this->actingAs($admin)
        ->get(route('status-permohonan.review', $contract))
        ->assertSuccessful()
        ->assertDontSee('Mengemukakan Borang JRP kepada KDN untuk mendapatkan ulasan/kelulusan daripada agensi berikut')
        ->getContent();

    preg_match('/class="jrp-checklist-note-wrap ml-6([^"]*)"[^>]*data-jrp-note-for="mof_negeri_budget_office"/', $html, $mofWrap);
    preg_match('/class="jrp-checklist-note-wrap ml-6([^"]*)"[^>]*data-jrp-note-for="epu_jpm_planning"/', $html, $epuWrap);

    expect($mofWrap)->not->toBeEmpty()
        ->and($mofWrap[1])->not->toContain('hidden')
        ->and($epuWrap)->not->toBeEmpty()
        ->and($epuWrap[1])->toContain('hidden')
        ->and($html)->toContain('Permohonan MOF sudah dihantar.')
        ->and($html)->toContain('name="jrp_checklist[notes][epu_jpm_planning]"');
});

test('admin hq autosave stores catatan for ticked mof and ke items and clears it when unticked', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);
    $contract = createHqReviewContractForAutosave();

    $this->actingAs($admin)
        ->patch(route('status-permohonan.checklist-autosave', $contract), [
            'jrp_checklist' => [
                HqJrpChecklist::MOF => '1',
                HqJrpChecklist::EPU => '1',
                'notes' => [
                    HqJrpChecklist::MOF => '  Catatan MOF  ',
                    HqJrpChecklist::EPU => 'Catatan KE',
                ],
            ],
        ])
        ->assertSuccessful();

    $contract->refresh();

    expect($contract->hq_jrp_checklist['notes'][HqJrpChecklist::MOF])->toBe('Catatan MOF')
        ->and($contract->hq_jrp_checklist['notes'][HqJrpChecklist::EPU])->toBe('Catatan KE');

    $this->actingAs($admin)
        ->patch(route('status-permohonan.checklist-autosave', $contract), [
            'jrp_checklist' => [
                HqJrpChecklist::MOF => '0',
                HqJrpChecklist::EPU => '0',
                'notes' => [
                    HqJrpChecklist::MOF => 'Catatan MOF',
                    HqJrpChecklist::EPU => 'Catatan KE',
                ],
            ],
        ])
        ->assertSuccessful();

    $contract->refresh();

    expect($contract->hq_jrp_checklist[HqJrpChecklist::MOF])->toBeFalse()
        ->and($contract->hq_jrp_checklist['notes'][HqJrpChecklist::MOF])->toBeNull()
        ->and($contract->hq_jrp_checklist['notes'][HqJrpChecklist::EPU])->toBeNull();
});

test('admin negeri cannot autosave hq jrp checklist', function () {
    $admin = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Melaka']);
    $contract = createHqReviewContractForAutosave('Melaka');

    $this->actingAs($admin)
        ->patch(route('status-permohonan.checklist-autosave', $contract), [
            'jrp_checklist' => [
                HqJrpChecklist::KP => '1',
            ],
        ])
        ->assertForbidden();
});
