<?php

use App\Models\ContractDocument;
use App\Models\Premise;
use App\Models\RentalContract;
use App\Models\User;
use App\Support\ApplicationCategories;
use App\Support\HqDraftAcknowledgements;
use App\Support\HqJrpChecklist;
use App\Support\NegeriDraftAcknowledgements;
use App\Support\NegeriMatiSetemAcknowledgements;
use App\Support\RentalStatusReport;
use App\Support\StatusTindakan;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('dashboard shell uses the corporate theme', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertSuccessful()
        ->assertSee('corp-app', false)
        ->assertSee('alt="SPEKTRA"', false)
        ->assertSee('images/spektra-logo.jpg', false)
        ->assertDontSee('E-SEWAAN', false)
        ->assertSee('#c4a35a', false)
        ->assertSee('#0e2744', false);
});

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

function createDashboardFollowUp(
    RentalContract $parent,
    string $workflow,
    string $kategori = ApplicationCategories::LANJUTAN,
): RentalContract {
    return RentalContract::query()->create([
        'parent_contract_id' => $parent->id,
        'premise_id' => $parent->premise_id,
        'tarikh_mula' => RentalContract::PLACEHOLDER_CONTRACT_DATE,
        'tarikh_tamat' => RentalContract::PLACEHOLDER_CONTRACT_DATE,
        'kadar_sewa_bulanan' => 1000,
        'status_aktif' => 'dalam_proses',
        'workflow_tahap' => $workflow,
        'kategori_permohonan' => $kategori,
    ]);
}

function attachDashboardPuuDocument(RentalContract $contract, string $status, int $round): void
{
    ContractDocument::query()->create([
        'contract_id' => $contract->id,
        'nama_fail' => 'draf-semakan-'.$round.'.pdf',
        'path' => 'contract-documents/'.$contract->id.'/draf-'.$round.'.pdf',
        'jenis' => ContractDocument::JENIS_DRAF_PERJANJIAN,
        'semakan_round' => $round,
        'semakan_status' => $status,
    ]);
}

/**
 * @return array<string, int>
 */
function dashboardStatusCounts(\Illuminate\Testing\TestResponse $response): array
{
    return collect($response->viewData('dalamTindakanByStatus'))
        ->mapWithKeys(fn (array $row): array => [$row['key'] => (int) $row['count']])
        ->all();
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
        ->assertViewHas('progressPermohonan', 23)
        ->assertViewHas('kontrakAktif', 2)
        ->assertViewHas('kontrakTigaBulan', 1)
        ->assertViewHas('kontrakLebihLapanBulan', 1)
        ->assertSee('Papan Pemuka')
        ->assertSee('Ringkasan permohonan dan kontrak sewaan untuk Johor');

    $tigaList = $response->viewData('alertListTigaBulan');
    $breakdown = $response->viewData('dalamTindakanByStatus');

    expect($tigaList)->toHaveCount(1)
        ->and($tigaList->first()->premise?->negeri)->toBe('Johor')
        ->and(collect($breakdown)->firstWhere('key', StatusTindakan::BARU_TERIMA_PERMOHONAN)['count'])->toBe(1);
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
        ->assertViewHas('progressPermohonan', 33)
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
        ->assertSee('Status Kontrak Sewaan Premis AADK')
        ->assertSee('Status Keseluruhan Portfolio')
        ->assertSee('Tindakan Segera')
        ->assertSee('Kontrak Sedang Berkuatkuasa')
        ->assertSee('Kurang 8 Bulan')
        ->assertDontSee('Taburan Baki Tempoh Kontrak')
        ->assertDontSee('Bilangan Mengikut Status Tindakan')
        ->assertDontSee('Dalam Tindakan – Mengikut Status')
        ->assertDontSee('Permohonan Terkini')
        ->assertDontSee('Status Aliran Kerja Permohonan')
        ->assertDontSee('Taburan permohonan mengikut peringkat proses');

    $breakdown = $response->viewData('dalamTindakanByStatus');

    expect($breakdown)->toHaveCount(13)
        ->and(collect($breakdown)->firstWhere('key', StatusTindakan::BARU_TERIMA_PERMOHONAN)['count'])->toBe(2)
        ->and(collect($breakdown)->firstWhere('key', StatusTindakan::SEDIA_DRAF_PERJANJIAN)['count'])->toBe(2)
        ->and(collect($breakdown)->firstWhere('key', StatusTindakan::SEMAKAN_CP_SEBELUM_PUU)['count'])->toBe(2)
        ->and(collect($breakdown)->firstWhere('key', StatusTindakan::SEMAKAN_PUU)['count'])->toBe(3)
        ->and(collect($breakdown)->firstWhere('key', StatusTindakan::KUIRI_PUU)['count'])->toBe(0)
        ->and(collect($breakdown)->sum('count'))->toBe(33);

    $tigaList = $response->viewData('alertListTigaBulan');
    $lapanList = $response->viewData('alertListLapanBulan');

    expect($tigaList)->toHaveCount(1)
        ->and($lapanList)->toHaveCount(1)
        ->and($tigaList->first()->id)->not->toBe($lapanList->first()->id);
});

test('dashboard portfolio pie groups contracts and lists urgent follow-ups', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);

    $inForce = createDashboardContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'Johor',
        now()->addMonths(18)->toDateString(),
    );
    $inForce->premise->update(['nama_ptj' => 'AADK Berkuatkuasa Johor']);

    $due = createDashboardContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'Pahang',
        now()->addMonths(5)->toDateString(),
    );
    $due->premise->update(['nama_ptj' => 'AADK Daerah Pekan']);

    $overdue = createDashboardContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'Sabah',
        now()->subMonths(3)->toDateString(),
    );
    $overdue->premise->update(['nama_ptj' => 'AADK Daerah Sandakan']);

    $puu = createDashboardContract(RentalContract::WORKFLOW_SEMAKAN_PUU, 'Kelantan');
    $puu->premise->update(['nama_ptj' => 'AADK Semakan PUU']);

    $bph = createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ, 'Kedah');
    $bph->update(['keluasan_mp' => 100]);
    $bph->premise->update(['nama_ptj' => 'AADK Pertimbangan BPH']);

    $sentParent = createDashboardContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'Melaka',
        now()->addMonths(4)->toDateString(),
    );
    $sentParent->premise->update(['nama_ptj' => 'AADK Sudah Hantar']);
    createDashboardFollowUp($sentParent, RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ);

    $response = $this->actingAs($admin)->get(route('dashboard'));
    $portfolio = $response->viewData('portfolio');
    $slices = collect($portfolio['slices'])->keyBy('key');

    $response->assertSuccessful()
        ->assertSee('AADK Daerah Pekan')
        ->assertSee('AADK Daerah Sandakan')
        ->assertSee('Belum Terima Permohonan')
        ->assertSee('bg-red-600', false)
        ->assertSee('bg-amber-400', false);

    expect($slices[RentalStatusReport::BUCKET_BERKUATKUASA]['count'])->toBe(1)
        ->and($slices[RentalStatusReport::SECTION_PUU]['count'])->toBe(1)
        ->and($slices[RentalStatusReport::SECTION_BPH]['count'])->toBe(2)
        ->and($slices[RentalStatusReport::SECTION_PTJ]['count'])->toBe(2)
        ->and($portfolio['total'])->toBe(6)
        ->and($portfolio['urgentCount'])->toBe(2)
        ->and($portfolio['urgentRows']->pluck('id')->all())->toBe([$overdue->id, $due->id])
        ->and($portfolio['urgentPercent'])->toBe(round((2 / 6) * 100, 1));
});

test('dashboard portfolio is scoped to the negeri admin', function () {
    $user = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Johor']);

    createDashboardContract(RentalContract::WORKFLOW_SEMAKAN_PUU, 'Johor');
    createDashboardContract(RentalContract::WORKFLOW_SEMAKAN_PUU, 'Melaka');
    createDashboardContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'Johor',
        now()->addMonths(4)->toDateString(),
    );
    createDashboardContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'Melaka',
        now()->addMonths(4)->toDateString(),
    );

    $portfolio = $this->actingAs($user)->get(route('dashboard'))->viewData('portfolio');
    $slices = collect($portfolio['slices'])->keyBy('key');

    expect($portfolio['total'])->toBe(2)
        ->and($slices[RentalStatusReport::SECTION_PUU]['count'])->toBe(1)
        ->and($portfolio['urgentCount'])->toBe(1)
        ->and($portfolio['urgentRows']->first()->premise?->negeri)->toBe('Johor');
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

test('status tindakan counts rise after hantar ke ibu pejabat and fall when each step is done', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);

    createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_PROCEED_NEGERI, 'Johor')->update([
        'semakan_count' => 1,
        'hq_jrp_checklist' => [
            HqJrpChecklist::AADK_RECEIVE_BPH_APPROVAL => true,
            HqJrpChecklist::MOF => true,
            HqJrpChecklist::AADK_RECEIVE_MOF_COMMENTS => true,
            HqJrpChecklist::AADK_RECEIVE_EPU_COMMENTS => true,
            HqJrpChecklist::AADK_SUBMIT_BPH => true,
        ],
        'negeri_draft_acknowledgements' => [
            NegeriDraftAcknowledgements::DOKUMEN_DITANDATANGANI => true,
        ],
        'hq_draft_acknowledgements' => [
            HqDraftAcknowledgements::PERJANJIAN_DITANDATANGANI => true,
        ],
        'negeri_mati_setem_acknowledgements' => [
            NegeriMatiSetemAcknowledgements::MATI_SETEM_LHDN => true,
        ],
    ]);

    createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ, 'Johor');

    createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ, 'Johor')->update([
        'hq_jrp_checklist' => [HqJrpChecklist::AADK_RECEIVE_BPH_APPROVAL => true],
    ]);
    createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ, 'Johor')->update([
        'hq_jrp_checklist' => [HqJrpChecklist::MOF => true],
    ]);
    createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ, 'Johor')->update([
        'hq_jrp_checklist' => [HqJrpChecklist::AADK_RECEIVE_MOF_COMMENTS => true],
    ]);
    createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ, 'Johor')->update([
        'hq_jrp_checklist' => [HqJrpChecklist::AADK_RECEIVE_EPU_COMMENTS => true],
    ]);
    createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ, 'Johor')->update([
        'hq_jrp_checklist' => [HqJrpChecklist::AADK_SUBMIT_BPH => true],
    ]);

    createDashboardContract(RentalContract::WORKFLOW_SEMAKAN_PUU, 'Johor')->update([
        'semakan_count' => 1,
    ]);

    $approved = createDashboardContract(RentalContract::WORKFLOW_DRAF_PERJANJIAN_LULUS, 'Johor');
    $approved->update(['semakan_count' => 1]);
    attachDashboardPuuDocument($approved, ContractDocument::SEMAKAN_DILULUSKAN, 1);

    $queried = createDashboardContract(RentalContract::WORKFLOW_PENYEDIAAN_DRAF_PERJANJIAN, 'Johor');
    $queried->update(['semakan_count' => 1]);
    attachDashboardPuuDocument($queried, ContractDocument::SEMAKAN_DIBATALKAN, 1);

    $queriedThenApproved = createDashboardContract(RentalContract::WORKFLOW_DRAF_PERJANJIAN_LULUS, 'Johor');
    $queriedThenApproved->update(['semakan_count' => 2]);
    attachDashboardPuuDocument($queriedThenApproved, ContractDocument::SEMAKAN_DIBATALKAN, 1);
    attachDashboardPuuDocument($queriedThenApproved, ContractDocument::SEMAKAN_DILULUSKAN, 2);

    createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ, 'Johor')->update([
        'negeri_draft_acknowledgements' => [
            NegeriDraftAcknowledgements::DOKUMEN_DITANDATANGANI => true,
        ],
    ]);
    createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ, 'Johor')->update([
        'hq_draft_acknowledgements' => [
            HqDraftAcknowledgements::PERJANJIAN_DITANDATANGANI => true,
        ],
    ]);
    createDashboardContract(RentalContract::WORKFLOW_MATI_SETEM, 'Johor')->update([
        'negeri_mati_setem_acknowledgements' => [
            NegeriMatiSetemAcknowledgements::MATI_SETEM_LHDN => true,
        ],
    ]);

    createDashboardContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'Johor',
        now()->addMonths(20)->toDateString(),
    );
    createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ, 'Johor')->update([
        'superseded_at' => now(),
    ]);
    createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ, 'Melaka');

    $counts = dashboardStatusCounts($this->actingAs($admin)->get(route('dashboard')));

    expect($counts[StatusTindakan::SEMAKAN_CP_SEBELUM_BPH])->toBe(13)
        ->and($counts[StatusTindakan::MINIT_CERAIAN_KELULUSAN_KOS])->toBe(13)
        ->and($counts[StatusTindakan::PERTIMBANGAN_MOF])->toBe(13)
        ->and($counts[StatusTindakan::PERTIMBANGAN_KE])->toBe(13)
        ->and($counts[StatusTindakan::PERTIMBANGAN_BPH_SUPS])->toBe(13)
        ->and($counts[StatusTindakan::SEDIA_DRAF_PERJANJIAN])->toBe(9)
        ->and($counts[StatusTindakan::SEMAKAN_CP_SEBELUM_PUU])->toBe(9)
        ->and($counts[StatusTindakan::SEMAKAN_PUU])->toBe(11)
        ->and($counts[StatusTindakan::KUIRI_PUU])->toBe(1)
        ->and($counts[StatusTindakan::PINDAAN_SYOR_PUU_TANDATANGAN_PEMILIK])->toBe(13)
        ->and($counts[StatusTindakan::TANDATANGAN_PENYEWA_AADK])->toBe(13)
        ->and($counts[StatusTindakan::TINDAKAN_METERI_SETEM])->toBe(13)
        ->and($counts[StatusTindakan::BARU_TERIMA_PERMOHONAN])->toBe(0);

    $negeriAdmin = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Johor']);
    $negeriCounts = dashboardStatusCounts($this->actingAs($negeriAdmin)->get(route('dashboard')));

    expect($negeriCounts[StatusTindakan::SEMAKAN_CP_SEBELUM_BPH])->toBe(12)
        ->and($negeriCounts[StatusTindakan::SEDIA_DRAF_PERJANJIAN])->toBe(8)
        ->and($negeriCounts[StatusTindakan::SEMAKAN_PUU])->toBe(10)
        ->and($negeriCounts[StatusTindakan::KUIRI_PUU])->toBe(1)
        ->and($negeriCounts[StatusTindakan::TINDAKAN_METERI_SETEM])->toBe(12);
});

test('belum terima permohonan decreases after the follow-up is sent to hq', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);

    createDashboardContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'Johor',
        now()->addMonths(4)->toDateString(),
    );

    $stillWithNegeri = createDashboardContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'Johor',
        now()->addMonths(5)->toDateString(),
    );
    createDashboardFollowUp($stillWithNegeri, RentalContract::WORKFLOW_MENUNGGU_PROCEED_NEGERI);

    $sentToHq = createDashboardContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'Johor',
        now()->addMonths(3)->toDateString(),
    );
    createDashboardFollowUp($sentToHq, RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ);

    $laterStage = createDashboardContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'Melaka',
        now()->addMonths(6)->toDateString(),
    );
    createDashboardFollowUp($laterStage, RentalContract::WORKFLOW_PENYEDIAAN_DRAF_PERJANJIAN, ApplicationCategories::PINDAH);

    $deletedFollowUp = createDashboardContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'Johor',
        now()->addMonths(2)->toDateString(),
    );
    createDashboardFollowUp($deletedFollowUp, RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ)->delete();

    createDashboardContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'Melaka',
        now()->addMonths(4)->toDateString(),
    );

    $breakdown = collect(
        $this->actingAs($admin)->get(route('dashboard'))->viewData('dalamTindakanByStatus')
    );

    expect($breakdown->firstWhere('key', StatusTindakan::BARU_TERIMA_PERMOHONAN)['count'])->toBe(4);

    $negeriAdmin = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Johor']);

    $negeriBreakdown = collect(
        $this->actingAs($negeriAdmin)->get(route('dashboard'))->viewData('dalamTindakanByStatus')
    );

    expect($negeriBreakdown->firstWhere('key', StatusTindakan::BARU_TERIMA_PERMOHONAN)['count'])->toBe(3);
});

test('status tindakan row opens the projects counted on that row', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);

    $hq = createDashboardContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ);
    $approved = createDashboardContract(RentalContract::WORKFLOW_DRAF_PERJANJIAN_LULUS);
    $within = createDashboardContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'Johor',
        now()->addMonths(2)->toDateString(),
    );
    $far = createDashboardContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'Johor',
        now()->addMonths(20)->toDateString(),
    );
    $otherNegeri = createDashboardContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'Melaka',
        now()->addMonths(2)->toDateString(),
    );

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertSuccessful()
        ->assertSee('kontrak', false)
        ->assertSee(route('status-permohonan.index', ['status_tindakan' => StatusTindakan::SEMAKAN_PUU]), false)
        ->assertSee(route('kontrak-sewaan.index', ['status_tindakan' => StatusTindakan::BARU_TERIMA_PERMOHONAN]), false);

    $semakanIds = collect(StatusTindakan::matchingIds(StatusTindakan::SEMAKAN_PUU))->sort()->values()->all();

    $semakan = $this->actingAs($admin)->get(route('status-permohonan.index', [
        'status_tindakan' => StatusTindakan::SEMAKAN_PUU,
    ]));

    $semakan->assertSuccessful()
        ->assertSee('Senarai projek bagi status')
        ->assertSee('Semakan PUU');

    expect($semakan->viewData('contracts')->pluck('id')->sort()->values()->all())->toBe($semakanIds)
        ->and($semakan->viewData('contracts')->pluck('id')->all())->toContain($hq->id)
        ->and($semakan->viewData('contracts')->pluck('id')->all())->not->toContain($approved->id);

    $this->actingAs($admin)
        ->get(route('status-permohonan.index', [
            'status_tindakan' => StatusTindakan::SEMAKAN_PUU,
            'partial' => 1,
        ]))
        ->assertSuccessful()
        ->assertSee('Semakan PUU');

    $belumIds = collect(StatusTindakan::matchingIds(StatusTindakan::BARU_TERIMA_PERMOHONAN))->sort()->values()->all();

    $belum = $this->actingAs($admin)->get(route('kontrak-sewaan.index', [
        'status_tindakan' => StatusTindakan::BARU_TERIMA_PERMOHONAN,
    ]));

    expect($belum->viewData('contracts')->pluck('id')->sort()->values()->all())->toBe($belumIds)
        ->and($belum->viewData('contracts')->pluck('id')->all())->toContain($within->id, $otherNegeri->id)
        ->and($belum->viewData('contracts')->pluck('id')->all())->not->toContain($far->id, $hq->id);

    $kuiri = $this->actingAs($admin)->get(route('status-permohonan.index', [
        'status_tindakan' => StatusTindakan::KUIRI_PUU,
    ]));

    expect($kuiri->viewData('contracts')->total())->toBe(0)
        ->and($kuiri->viewData('statusTindakanLabel'))->toBe('Kuiri PUU');

    $negeri = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Johor']);

    $negeriBelum = $this->actingAs($negeri)->get(route('kontrak-sewaan.index', [
        'status_tindakan' => StatusTindakan::BARU_TERIMA_PERMOHONAN,
    ]));

    expect($negeriBelum->viewData('contracts')->pluck('id')->all())
        ->toContain($within->id)
        ->not->toContain($otherNegeri->id);
});
