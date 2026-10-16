<?php

use App\Models\Premise;
use App\Models\RentalContract;
use App\Models\User;
use App\Services\HqReportService;
use App\Support\ApplicationCategories;
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
