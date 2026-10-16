<?php

use App\Models\Premise;
use App\Models\RentalContract;
use App\Models\User;
use App\Support\ApplicationCategories;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createDashboardContract(
    string $workflow,
    string $negeri = 'Johor',
    ?string $sahSehingga = null,
): RentalContract {
    $premise = Premise::query()->create([
        'nama_ptj' => 'Premis Dashboard '.uniqid(),
        'negeri' => $negeri,
        'daerah' => $negeri,
        'alamat_penuh' => 'Alamat ujian',
        'nama_pemilik' => 'Pemilik',
        'kadar_sewa' => 1000,
    ]);

    $hasRealContractDates = filled($sahSehingga);

    return RentalContract::query()->create([
        'premise_id' => $premise->id,
        'tarikh_mula' => $hasRealContractDates
            ? now()->subMonths(2)->toDateString()
            : RentalContract::PLACEHOLDER_CONTRACT_DATE,
        'tarikh_tamat' => $hasRealContractDates
            ? $sahSehingga
            : RentalContract::PLACEHOLDER_CONTRACT_DATE,
        'kadar_sewa_bulanan' => 1000,
        'status_aktif' => 'dalam_proses',
        'workflow_tahap' => $workflow,
        'kategori_permohonan' => ApplicationCategories::BARU,
        'hq_approved_at' => now(),
        'sah_sehingga' => $sahSehingga,
    ]);
}

test('admin negeri can view dashboard scoped to their negeri only', function () {
    $user = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Johor']);

    createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ, 'Johor');
    createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ, 'Melaka');
    createDashboardContract(RentalContract::WORKFLOW_PENYEDIAAN_DRAF_PERJANJIAN, 'Johor');
    createDashboardContract(RentalContract::WORKFLOW_PENYEDIAAN_DRAF_PERJANJIAN, 'Melaka');
    createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI, 'Johor', now()->addMonths(2)->toDateString());
    createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI, 'Melaka', now()->addMonths(2)->toDateString());
    createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI, 'Johor', now()->addMonths(20)->toDateString());

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertSuccessful()
        ->assertViewHas('scopedNegeri', 'Johor')
        ->assertViewHas('permohonanBaharu', 1)
        ->assertViewHas('progressPermohonan', 2)
        ->assertViewHas('kontrakAktif', 2)
        ->assertViewHas('kontrakTigaBulan', 1)
        ->assertViewHas('kontrakLebihLapanBulan', 1)
        ->assertSee('Papan Pemuka')
        ->assertSee('Ringkasan permohonan dan kontrak sewaan untuk Johor');

    $tigaList = $response->viewData('alertListTigaBulan');
    $breakdown = $response->viewData('dalamTindakanByStatus');

    expect($tigaList)->toHaveCount(1)
        ->and($tigaList->first()->premise?->negeri)->toBe('Johor')
        ->and(collect($breakdown)->firstWhere('key', \App\Support\StatusTindakan::BARU_TERIMA_PERMOHONAN)['count'])->toBe(1);
});

test('dashboard shows accurate counts based on current workflow data', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);

    createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ);
    createDashboardContract(RentalContract::WORKFLOW_PENYEDIAAN_DRAF_PERJANJIAN);
    createDashboardContract(RentalContract::WORKFLOW_SEMAKAN_PUU);
    createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI, 'Johor', now()->addMonths(4)->toDateString());
    createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI, 'Johor', now()->addMonths(2)->toDateString());
    createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI, 'Johor', now()->addMonths(20)->toDateString());

    $response = $this->actingAs($admin)->get(route('dashboard'));

    $response->assertSuccessful()
        ->assertViewHas('permohonanBaharu', 1)
        ->assertViewHas('progressPermohonan', 4)
        ->assertViewHas('kontrakAktif', 3)
        ->assertViewHas('kontrakLapanBulan', 1)
        ->assertViewHas('kontrakTigaBulan', 1)
        ->assertViewHas('kontrakLebihLapanBulan', 1)
        ->assertSee('Papan Pemuka')
        ->assertSee('Permohonan Baharu')
        ->assertSee('Dalam Tindakan')
        ->assertSee('Status Tindakan')
        ->assertSee('Belum Terima Permohonan')
        ->assertSee('Sedia Draf Perjanjian')
        ->assertSee('Semakan PUU')
        ->assertSee('Tindakan Mati Setem')
        ->assertSee('Kontrak Aktif')
        ->assertSee('Kontrak Bawah 8 Bulan')
        ->assertSee('Kontrak Bawah 3 Bulan')
        ->assertSee('Taburan Baki Tempoh Kontrak')
        ->assertSee('Bilangan Mengikut Status Tindakan')
        ->assertDontSee('Dalam Tindakan – Mengikut Status')
        ->assertDontSee('Permohonan Terkini')
        ->assertDontSee('Status Aliran Kerja Permohonan')
        ->assertDontSee('Taburan permohonan mengikut peringkat proses');

    $breakdown = $response->viewData('dalamTindakanByStatus');

    expect($breakdown)->toHaveCount(13)
        ->and(collect($breakdown)->firstWhere('key', \App\Support\StatusTindakan::BARU_TERIMA_PERMOHONAN)['count'])->toBe(2)
        ->and(collect($breakdown)->firstWhere('key', \App\Support\StatusTindakan::SEDIA_DRAF_PERJANJIAN)['count'])->toBe(1)
        ->and(collect($breakdown)->firstWhere('key', \App\Support\StatusTindakan::SEMAKAN_PUU)['count'])->toBe(1)
        ->and(collect($breakdown)->sum('count'))->toBe(4);

    $tigaList = $response->viewData('alertListTigaBulan');
    $lapanList = $response->viewData('alertListLapanBulan');

    expect($tigaList)->toHaveCount(1)
        ->and($lapanList)->toHaveCount(1)
        ->and($tigaList->first()->id)->not->toBe($lapanList->first()->id);
});

test('dashboard dalam tindakan card links to status breakdown section', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertSuccessful()
        ->assertSee('href="#dalam-tindakan"', false)
        ->assertSee('id="dalam-tindakan"', false);
});

test('dashboard excludes superseded contracts from active count', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);

    $active = createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI, 'Johor', now()->addMonths(20)->toDateString());
    $superseded = createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI, 'Johor', now()->addMonths(20)->toDateString());
    $superseded->update(['superseded_at' => now()]);

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertSuccessful()
        ->assertViewHas('kontrakAktif', 1);
});

test('ticking aadk submit bph checklist counts toward semakan cp sebelum bph', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);

    $triggered = createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ, 'Johor');
    $triggered->update([
        'hq_jrp_checklist' => [
            \App\Support\HqJrpChecklist::KP => true,
            \App\Support\HqJrpChecklist::MOF => false,
            \App\Support\HqJrpChecklist::EPU => false,
            \App\Support\HqJrpChecklist::AADK_RECEIVE_EPU_COMMENTS => false,
            \App\Support\HqJrpChecklist::AADK_RECEIVE_MOF_COMMENTS => false,
            \App\Support\HqJrpChecklist::AADK_SUBMIT_BPH => true,
            \App\Support\HqJrpChecklist::AADK_RECEIVE_BPH_APPROVAL => false,
            'dates' => [
                \App\Support\HqJrpChecklist::AADK_SUBMIT_BPH => '2026-10-16',
            ],
        ],
    ]);

    $unticked = createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ, 'Johor');
    $unticked->update([
        'hq_jrp_checklist' => [
            \App\Support\HqJrpChecklist::AADK_SUBMIT_BPH => false,
        ],
    ]);

    $otherNegeri = createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ, 'Melaka');
    $otherNegeri->update([
        'hq_jrp_checklist' => [
            \App\Support\HqJrpChecklist::AADK_SUBMIT_BPH => true,
            'dates' => [
                \App\Support\HqJrpChecklist::AADK_SUBMIT_BPH => '2026-10-16',
            ],
        ],
    ]);

    $completed = createDashboardContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'Johor',
        now()->addMonths(12)->toDateString(),
    );
    $completed->update([
        'hq_jrp_checklist' => [
            \App\Support\HqJrpChecklist::AADK_SUBMIT_BPH => true,
            'dates' => [
                \App\Support\HqJrpChecklist::AADK_SUBMIT_BPH => '2026-10-16',
            ],
        ],
    ]);

    $response = $this->actingAs($admin)->get(route('dashboard'));

    $breakdown = collect($response->viewData('dalamTindakanByStatus'));

    expect($breakdown->firstWhere('key', \App\Support\StatusTindakan::SEMAKAN_CP_SEBELUM_BPH)['count'])->toBe(2);

    $negeriAdmin = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Johor']);

    $negeriBreakdown = collect(
        $this->actingAs($negeriAdmin)->get(route('dashboard'))->viewData('dalamTindakanByStatus')
    );

    expect($negeriBreakdown->firstWhere('key', \App\Support\StatusTindakan::SEMAKAN_CP_SEBELUM_BPH)['count'])->toBe(1);
});
