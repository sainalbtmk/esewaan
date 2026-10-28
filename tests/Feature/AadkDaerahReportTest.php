<?php

use App\Models\AadkDaerah;
use App\Models\Premise;
use App\Models\RentalContract;
use App\Models\User;
use App\Services\HqReportService;
use App\Support\ApplicationCategories;
use App\Support\MalaysianStates;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('aadk daerah reference covers every state with a dedicated daerah column', function () {
    $records = AadkDaerah::query()->orderBy('susunan')->get();

    expect($records)->toHaveCount(113)
        ->and($records->pluck('negeri')->unique()->sort()->values()->all())
        ->toEqual(collect(MalaysianStates::values())->sort()->values()->all())
        ->and($records->firstWhere('daerah', 'Johor Bahru')?->emel)->toBe('kd_johorbahru@aadk.gov.my')
        ->and($records->firstWhere('daerah', 'Johor Bahru')?->nama_pejabat)->toBe('AADK Daerah Johor Bahru')
        ->and($records->where('negeri', 'W.P. Kuala Lumpur')->pluck('daerah')->all())->not->toContain('Putrajaya')
        ->and($records->where('negeri', 'W.P. Putrajaya')->pluck('daerah')->all())->toBe(['Putrajaya']);
});

test('daerah filter appears after a negeri is selected and limits every report', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);

    $muar = createDaerahReportContract('Muar', RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ);
    $kluang = createDaerahReportContract('Kluang', RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ);
    createDaerahReportContract('Kota Bharu', RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ, 'Kelantan');

    $this->actingAs($admin)
        ->get(route('reports.index'))
        ->assertSuccessful()
        ->assertSee('id="daerah-field" class="lg:col-span-3 hidden"', false)
        ->assertDontSee('>Johor Bahru</option>', false);

    $response = $this->actingAs($admin)
        ->get(route('reports.index', [
            'jenis' => HqReportService::TYPE_PERMOHONAN,
            'negeri' => 'Johor',
            'daerah' => 'Muar',
        ]));

    $response->assertSuccessful()
        ->assertSee('id="daerah-field" class="lg:col-span-3"', false)
        ->assertDontSee('id="daerah-field" class="lg:col-span-3 hidden"', false)
        ->assertSee('>Muar</option>', false)
        ->assertSee('>Kluang</option>', false)
        ->assertSee('Skop: Johor · Daerah Muar')
        ->assertSee('Pecahan Mengikut Daerah')
        ->assertSee($muar->premise->nama_ptj);

    expect($response->viewData('report')['rows']->pluck('id')->all())->toBe([$muar->id])
        ->and($response->viewData('report')['rows']->pluck('id')->all())->not->toContain($kluang->id)
        ->and($response->viewData('filters')['daerah'])->toBe('Muar');

    $kontrakNegeri = $this->actingAs($admin)
        ->get(route('reports.index', [
            'jenis' => HqReportService::TYPE_KONTRAK_NEGERI,
            'negeri' => 'Johor',
            'daerah' => 'Muar',
        ]));

    $kontrakNegeri->assertSuccessful()
        ->assertSee('Laporan Kontrak AADK Negeri Johor — Daerah Muar')
        ->assertSee('>Daerah</th>', false)
        ->assertSee($muar->premise->nama_ptj);

    expect($kontrakNegeri->viewData('report')['rows']->pluck('id')->all())->toBe([$muar->id]);

    $this->actingAs($admin)
        ->get(route('reports.pdf', [
            'jenis' => HqReportService::TYPE_KONTRAK_AKTIF,
            'negeri' => 'Johor',
            'daerah' => 'Muar',
        ]))
        ->assertSuccessful()
        ->assertHeader('content-type', 'application/pdf');
});

test('report rejects a daerah that does not belong to the selected negeri', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);

    $this->actingAs($admin)
        ->from(route('reports.index'))
        ->get(route('reports.index', [
            'negeri' => 'Johor',
            'daerah' => 'Petaling',
        ]))
        ->assertRedirect(route('reports.index'))
        ->assertSessionHasErrors('daerah');
});

function createDaerahReportContract(
    string $daerah,
    string $workflow,
    string $negeri = 'Johor',
): RentalContract {
    $premise = Premise::query()->create([
        'nama_ptj' => 'AADK Daerah '.$daerah,
        'negeri' => $negeri,
        'daerah' => $daerah,
        'alamat_penuh' => 'Alamat '.$daerah,
        'nama_pemilik' => 'Pemilik '.$daerah,
        'kadar_sewa' => 1200,
    ]);

    return RentalContract::query()->create([
        'premise_id' => $premise->id,
        'tarikh_mula' => RentalContract::PLACEHOLDER_CONTRACT_DATE,
        'tarikh_tamat' => RentalContract::PLACEHOLDER_CONTRACT_DATE,
        'kadar_sewa_bulanan' => 1200,
        'status_aktif' => 'dalam_proses',
        'workflow_tahap' => $workflow,
        'kategori_permohonan' => ApplicationCategories::BARU,
    ]);
}
