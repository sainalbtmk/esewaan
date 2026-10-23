<?php

use App\Models\Premise;
use App\Models\ProcessLog;
use App\Models\RentalContract;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\HqReportService;
use App\Support\ApplicationCategories;
use App\Support\BuildingTypes;
use App\Support\HqJrpChecklist;
use App\Support\RentalStatusReport;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createReportContract(
    string $workflow,
    string $negeri = 'Johor',
    ?string $sahSehingga = null,
): RentalContract {
    $premise = Premise::query()->create([
        'nama_ptj' => 'Premis Laporan '.uniqid(),
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
        'hq_approved_at' => $workflow === RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI ? now() : null,
        'sah_sehingga' => $sahSehingga,
    ]);
}

test('admin hq can view jana laporan page and sidebar link', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);

    createReportContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ, 'Johor');
    createReportContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI, 'Melaka', now()->addMonths(10)->toDateString());

    $this->actingAs($admin)
        ->get(route('reports.index'))
        ->assertSuccessful()
        ->assertSee('Jana Laporan')
        ->assertSee('Eksport PDF')
        ->assertSee('Permohonan Baharu')
        ->assertSee('Kontrak Aktif')
        ->assertSee(route('reports.index'), false);
});

test('admin hq can filter report by negeri and jenis', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);

    $johor = createReportContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ, 'Johor');
    $melaka = createReportContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ, 'Melaka');

    $response = $this->actingAs($admin)
        ->get(route('reports.index', [
            'jenis' => HqReportService::TYPE_PERMOHONAN,
            'negeri' => 'Johor',
        ]));

    $response->assertSuccessful()
        ->assertSee('Skop: Johor')
        ->assertSee($johor->premise->nama_ptj);

    expect($response->viewData('filters')['negeri'])->toBe('Johor')
        ->and($response->viewData('report')['rows']->pluck('id')->all())
        ->toBe([$johor->id])
        ->and($response->viewData('report')['rows']->pluck('id')->all())
        ->not->toContain($melaka->id);
});

test('admin hq can generate laporan kontrak mengikut negeri', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);
    $end = now()->addDays(10)->startOfDay();

    $kelantan = createReportContract(
        RentalContract::WORKFLOW_SEMAKAN_PUU,
        'Kelantan',
        $end->toDateString(),
    );
    $kelantan->premise->update([
        'nama_ptj' => 'AADK Negeri Kelantan',
        'alamat_penuh' => 'Tingkat 1, Menara Perbadanan, Kota Bharu',
        'nama_pemilik' => 'Perbadanan Hartanah Kelantan',
        'jenis_bangunan' => BuildingTypes::KOMPLEKS_KERAJAAN,
    ]);
    $kelantan->update([
        'kategori_permohonan' => ApplicationCategories::LANJUTAN,
        'keluasan_mp' => 1219,
        'kadar_sewa_bulanan' => 28597.14,
        'tarikh_tamat' => $end->toDateString(),
        'hq_jrp_checklist' => [
            HqJrpChecklist::AADK_SUBMIT_BPH => true,
            'dates' => [
                HqJrpChecklist::AADK_SUBMIT_BPH => '2026-01-14',
            ],
        ],
    ]);

    ProcessLog::query()->create([
        'contract_id' => $kelantan->id,
        'tarikh_tindakan' => '2025-11-20',
        'jenis_tindakan' => 'Emel penjelasan',
        'keterangan' => 'Emel penjelasan pengabaian permohonan pelanjutan telah dihantar kepada AADK Negeri.',
    ]);

    ActivityLogger::log(
        $admin,
        'puu_review_approved',
        'Semakan PUU untuk premis AADK Negeri Kelantan direkodkan.',
        ['contract_id' => $kelantan->id],
    );

    $melaka = createReportContract(
        RentalContract::WORKFLOW_SEMAKAN_PUU,
        'Melaka',
        now()->addMonths(6)->toDateString(),
    );
    $melaka->premise->update(['nama_ptj' => 'AADK Negeri Melaka']);

    $superseded = createReportContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'Kelantan',
        now()->addYear()->toDateString(),
    );
    $superseded->premise->update(['nama_ptj' => 'Premis Diganti Kelantan']);
    $superseded->update(['superseded_at' => now()]);

    $bakiHari = (string) $kelantan->fresh()->daysUntilContractEnd();

    $response = $this->actingAs($admin)
        ->get(route('reports.index', [
            'jenis' => HqReportService::TYPE_KONTRAK_NEGERI,
            'negeri' => 'Kelantan',
        ]));

    $response->assertSuccessful()
        ->assertSee('Laporan Kontrak mengikut Negeri')
        ->assertSee('Laporan Kontrak AADK Negeri Kelantan')
        ->assertSee('Laporan secara individu beserta kronologi')
        ->assertSee('Perincian Proses Cawangan Pembangunan')
        ->assertSee('AADK Negeri Kelantan')
        ->assertSee('Tingkat 1, Menara Perbadanan, Kota Bharu')
        ->assertSee('Kompleks kerajaan')
        ->assertSee('RM28,597.14')
        ->assertSee('1,219')
        ->assertSee('Perbadanan Hartanah Kelantan')
        ->assertSee('LANJUTAN')
        ->assertSee('Terima')
        ->assertSee('Semakan PUU')
        ->assertSee('PUU')
        ->assertSee($bakiHari)
        ->assertSee('bg-red-600', false)
        ->assertSee('Emel penjelasan pengabaian permohonan pelanjutan telah dihantar kepada AADK Negeri.')
        ->assertSee('14/01/2026 – '.HqJrpChecklist::definitions()[HqJrpChecklist::AADK_SUBMIT_BPH])
        ->assertSee('Semakan PUU untuk premis AADK Negeri Kelantan direkodkan.')
        ->assertSeeInOrder([
            '20/11/2025 – Emel penjelasan pengabaian permohonan pelanjutan telah dihantar kepada AADK Negeri.',
            '14/01/2026 – '.HqJrpChecklist::definitions()[HqJrpChecklist::AADK_SUBMIT_BPH],
            'Semakan PUU untuk premis AADK Negeri Kelantan direkodkan.',
        ]);

    expect($response->viewData('report')['rows']->pluck('id')->all())->toBe([$kelantan->id]);
});

test('laporan kontrak mengikut negeri marks applications not yet received', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);

    $contract = createReportContract(RentalContract::WORKFLOW_MENUNGGU_PROCEED_NEGERI, 'Johor');
    $contract->premise->update(['nama_ptj' => 'Premis Belum Dihantar Johor']);

    $this->actingAs($admin)
        ->get(route('reports.index', [
            'jenis' => HqReportService::TYPE_KONTRAK_NEGERI,
            'negeri' => 'Johor',
        ]))
        ->assertSuccessful()
        ->assertSee('Premis Belum Dihantar Johor')
        ->assertSee('Belum Terima')
        ->assertSee('Menunggu Tindakan Negeri')
        ->assertSee('Tiada kronologi.');
});

test('admin hq can generate laporan status sewaan grouped by current stage', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);

    $bph = createReportContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ,
        'Kelantan',
        now()->addMonths(6)->toDateString(),
    );
    $bph->premise->update(['nama_ptj' => 'AADK BPH Kelantan']);
    $bph->update([
        'kategori_permohonan' => ApplicationCategories::PINDAH,
        'keluasan_mp' => 100,
        'kadar_sewa_bulanan' => 3500,
    ]);

    $parent = createReportContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'Kelantan',
        now()->addMonths(18)->toDateString(),
    );
    $parent->premise->update(['nama_ptj' => 'Induk MOF Kelantan']);
    $parent->update(['kadar_sewa_bulanan' => 1000]);

    $mofEnd = now()->subMonths(2)->startOfDay();
    $mof = createReportContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ,
        'Kelantan',
        $mofEnd->toDateString(),
    );
    $mof->premise->update(['nama_ptj' => 'AADK MOF Kelantan']);
    $mof->update([
        'parent_contract_id' => $parent->id,
        'kategori_permohonan' => ApplicationCategories::LANJUTAN,
        'keluasan_mp' => 120,
        'kadar_sewa_bulanan' => 2000,
        'tarikh_tamat' => $mofEnd->toDateString(),
    ]);

    $puu = createReportContract(
        RentalContract::WORKFLOW_SEMAKAN_PUU,
        'Kelantan',
        now()->addMonths(4)->toDateString(),
    );
    $puu->premise->update(['nama_ptj' => 'AADK PUU Kelantan']);

    $ptj = createReportContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'Kelantan',
        now()->addMonths(4)->toDateString(),
    );
    $ptj->premise->update(['nama_ptj' => 'AADK Belum Terima Kelantan']);

    $stable = createReportContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'Kelantan',
        now()->addMonths(18)->toDateString(),
    );
    $stable->premise->update(['nama_ptj' => 'AADK Stabil Kelantan']);

    createReportContract(
        RentalContract::WORKFLOW_SEMAKAN_PUU,
        'Johor',
        now()->addMonths(3)->toDateString(),
    )->premise->update(['nama_ptj' => 'AADK PUU Johor']);

    $response = $this->actingAs($admin)
        ->get(route('reports.index', [
            'jenis' => HqReportService::TYPE_STATUS_SEWAAN,
            'negeri' => 'Kelantan',
        ]));

    $response->assertSuccessful()
        ->assertSee('Laporan Status Sewaan')
        ->assertSee('Laporan Status Sewaan Premis AADK setakat')
        ->assertSee('Lampiran 1')
        ->assertSee('Baki Bulan')
        ->assertSeeInOrder([
            'Pertimbangan BPH (1)',
            'Semakan PUU (1)',
            'Pertimbangan MOF (1)',
            'Tindakan PTJ (1)',
        ])
        ->assertSee('AADK BPH Kelantan')
        ->assertSee('PINDAH')
        ->assertSee('AADK PUU Kelantan')
        ->assertSee('AADK MOF Kelantan')
        ->assertSee('PELANJUTAN')
        ->assertSee('Pertimbangan MOF')
        ->assertSee((string) RentalStatusReport::bakiBulan($mof->fresh()))
        ->assertSee('text-red-600', false)
        ->assertSee('AADK Belum Terima Kelantan')
        ->assertSee('Belum Terima Permohonan')
        ->assertSee('Belum Terima');

    $groups = collect($response->viewData('report')['statusGroups']);

    expect($groups->pluck('key')->all())->toBe([
        RentalStatusReport::SECTION_BPH,
        RentalStatusReport::SECTION_PUU,
        RentalStatusReport::SECTION_MOF,
        RentalStatusReport::SECTION_PTJ,
    ])
        ->and($groups->firstWhere('key', RentalStatusReport::SECTION_BPH)['rows']->pluck('id')->all())->toBe([$bph->id])
        ->and($groups->firstWhere('key', RentalStatusReport::SECTION_PUU)['rows']->pluck('id')->all())->toBe([$puu->id])
        ->and($groups->firstWhere('key', RentalStatusReport::SECTION_MOF)['rows']->pluck('id')->all())->toBe([$mof->id])
        ->and($groups->firstWhere('key', RentalStatusReport::SECTION_PTJ)['rows']->pluck('id')->all())->toBe([$ptj->id])
        ->and($response->viewData('report')['rows']->pluck('id')->all())
        ->not->toContain($stable->id)
        ->not->toContain($parent->id);
});

test('laporan status sewaan sorts tindakan ptj by remaining months from lowest', function () {
    $later = createReportContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'Johor',
        now()->addMonths(6)->toDateString(),
    );
    $later->premise->update(['nama_ptj' => 'AADK Johor Lewat']);

    $sooner = createReportContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'Kedah',
        now()->addMonth()->toDateString(),
    );
    $sooner->premise->update(['nama_ptj' => 'AADK Kedah Segera']);

    $alsoSoon = createReportContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'Kedah',
        now()->addMonth()->toDateString(),
    );
    $alsoSoon->premise->update(['nama_ptj' => 'AADK Alor Setar Segera']);

    $contracts = RentalContract::query()
        ->with('premise')
        ->whereIn('id', [$later->id, $sooner->id, $alsoSoon->id])
        ->get();

    $ptj = collect(RentalStatusReport::groups($contracts))
        ->firstWhere('key', RentalStatusReport::SECTION_PTJ);

    expect(RentalStatusReport::bakiBulan($sooner->fresh()))->toBeLessThan(RentalStatusReport::bakiBulan($later->fresh()))
        ->and($ptj['rows']->pluck('id')->all())->toBe([
            $alsoSoon->id,
            $sooner->id,
            $later->id,
        ]);
});

test('laporan status sewaan places large premises under pertimbangan ke', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);

    $contract = createReportContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ,
        'Pahang',
        now()->addMonths(5)->toDateString(),
    );
    $contract->update(['keluasan_mp' => 500]);
    $contract->premise->update(['nama_ptj' => 'AADK KE Pahang']);

    $this->actingAs($admin)
        ->get(route('reports.index', [
            'jenis' => HqReportService::TYPE_STATUS_SEWAAN,
            'negeri' => 'Pahang',
        ]))
        ->assertSuccessful()
        ->assertSee('Pertimbangan KE (1)')
        ->assertSee('AADK KE Pahang')
        ->assertSee('Pertimbangan KE');
});

test('admin hq can export laporan status sewaan as pdf', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);

    createReportContract(
        RentalContract::WORKFLOW_SEMAKAN_PUU,
        'Kelantan',
        now()->addMonths(4)->toDateString(),
    );

    $this->actingAs($admin)
        ->get(route('reports.pdf', [
            'jenis' => HqReportService::TYPE_STATUS_SEWAAN,
            'negeri' => 'Kelantan',
        ]))
        ->assertSuccessful()
        ->assertHeader('content-type', 'application/pdf');
});

test('admin hq can export laporan kontrak mengikut negeri as pdf', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);

    createReportContract(
        RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'Kelantan',
        now()->addMonths(4)->toDateString(),
    );

    $this->actingAs($admin)
        ->get(route('reports.pdf', [
            'jenis' => HqReportService::TYPE_KONTRAK_NEGERI,
            'negeri' => 'Kelantan',
        ]))
        ->assertSuccessful()
        ->assertHeader('content-type', 'application/pdf');
});

test('admin hq can export report as pdf', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);

    createReportContract(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI, 'Johor', now()->addMonths(6)->toDateString());

    $this->actingAs($admin)
        ->get(route('reports.pdf', ['jenis' => HqReportService::TYPE_KONTRAK_AKTIF]))
        ->assertSuccessful()
        ->assertHeader('content-type', 'application/pdf');
});

test('admin negeri cannot access jana laporan', function () {
    $admin = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Johor']);

    $this->actingAs($admin)
        ->get(route('reports.index'))
        ->assertForbidden();

    $this->actingAs($admin)
        ->get(route('reports.pdf'))
        ->assertForbidden();
});

test('guest is redirected from jana laporan', function () {
    $this->get(route('reports.index'))
        ->assertRedirect(route('login'));
});

test('jana laporan link is hidden from negeri sidebar', function () {
    $admin = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Johor']);

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertSuccessful()
        ->assertDontSee('Jana Laporan')
        ->assertDontSee(route('reports.index'), false);
});
