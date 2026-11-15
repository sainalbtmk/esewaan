<?php

use App\Mail\ApplicationWorkflowStageNotification;
use App\Models\EmelJawatan;
use App\Models\Premise;
use App\Models\RentalContract;
use App\Models\User;
use App\Support\ApplicationCategories;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

function createWorkflowMailContract(User $negeriAdmin, string $workflow): RentalContract
{
    $premise = Premise::query()->create([
        'nama_ptj' => 'Premis Emel Stepper',
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
        'kadar_sewa_bulanan' => 0,
        'status_aktif' => 'dalam_proses',
        'workflow_tahap' => $workflow,
        'kategori_permohonan' => ApplicationCategories::BARU,
        'semakan_count' => 1,
    ]);
}

function assertStageMailSentToHqAndNegeri(User $hq, User $negeri, string $subjectContains, ?string $bodyContains = null): void
{
    Mail::assertSent(ApplicationWorkflowStageNotification::class, function (ApplicationWorkflowStageNotification $mail) use ($hq, $subjectContains, $bodyContains): bool {
        return $mail->hasTo($hq->email)
            && str_contains($mail->envelope()->subject, $subjectContains)
            && ($bodyContains === null || str_contains($mail->render(), $bodyContains));
    });

    Mail::assertSent(ApplicationWorkflowStageNotification::class, function (ApplicationWorkflowStageNotification $mail) use ($negeri, $subjectContains, $bodyContains): bool {
        return $mail->hasTo($negeri->email)
            && str_contains($mail->envelope()->subject, $subjectContains)
            && ($bodyContains === null || str_contains($mail->render(), $bodyContains));
    });

    foreach (EmelJawatan::query()->orderBy('susunan')->pluck('email') as $email) {
        Mail::assertSent(ApplicationWorkflowStageNotification::class, function (ApplicationWorkflowStageNotification $mail) use ($email, $subjectContains, $bodyContains): bool {
            return $mail->hasTo($email)
                && str_contains($mail->envelope()->subject, $subjectContains)
                && ($bodyContains === null || str_contains($mail->render(), $bodyContains));
        });
    }
}

test('uploading draft agreement emails hq and negeri for puu stage', function () {
    Mail::fake();
    Storage::fake('local');

    $negeri = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Johor']);
    $hq = User::factory()->create(['role' => 'admin_hq']);
    $contract = createWorkflowMailContract($negeri, RentalContract::WORKFLOW_PENYEDIAAN_DRAF_PERJANJIAN);

    $this->actingAs($negeri)
        ->post(route('status-permohonan.upload-draft', $contract), [
            'document' => UploadedFile::fake()->create('draf.pdf', 100, 'application/pdf'),
        ])
        ->assertRedirect();

    assertStageMailSentToHqAndNegeri($hq, $negeri, 'Semakan PUU', 'Peringkat 4 — Dalam tindakan PUU (Semakan PUU 2)');
});

test('rejecting puu review emails hq and negeri for pindaan stage', function () {
    Mail::fake();
    Storage::fake('local');

    $negeri = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Johor']);
    $hq = User::factory()->create(['role' => 'admin_hq']);
    $contract = createWorkflowMailContract($negeri, RentalContract::WORKFLOW_SEMAKAN_PUU);

    $this->actingAs($hq)
        ->post(route('status-permohonan.reject-puu', $contract), [
            'document' => UploadedFile::fake()->create('pindaan-puu.pdf', 100, 'application/pdf'),
        ])
        ->assertRedirect();

    assertStageMailSentToHqAndNegeri($hq, $negeri, 'Pindaan', 'Peringkat 3 — Penyediaan Draf (Kuiri PUU)');
});

test('approving puu review emails hq and negeri for draf lulus stage', function () {
    Mail::fake();
    Storage::fake('local');

    $negeri = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Johor']);
    $hq = User::factory()->create(['role' => 'admin_hq']);
    $contract = createWorkflowMailContract($negeri, RentalContract::WORKFLOW_SEMAKAN_PUU);

    $this->actingAs($hq)
        ->post(route('status-permohonan.approve-puu', $contract), [
            'document' => UploadedFile::fake()->create('dokumen-lulus.pdf', 100, 'application/pdf'),
        ])
        ->assertRedirect();

    assertStageMailSentToHqAndNegeri($hq, $negeri, 'Draf Perjanjian Diluluskan', 'Peringkat 5 — Draf Lulus');
});

test('completing pindaan emails hq and negeri for draf lulus stage', function () {
    Mail::fake();

    $negeri = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Melaka']);
    $hq = User::factory()->create(['role' => 'admin_hq']);
    $contract = createWorkflowMailContract($negeri, RentalContract::WORKFLOW_PINDAAN_BERDASARKAN_PUU);

    $this->actingAs($hq)
        ->post(route('status-permohonan.complete', $contract))
        ->assertRedirect();

    assertStageMailSentToHqAndNegeri($hq, $negeri, 'Draf Perjanjian Diluluskan', 'Peringkat 5 — Draf Lulus');
});

test('returning draft to hq emails both roles for pengesahan stage', function () {
    Mail::fake();

    $negeri = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Johor']);
    $hq = User::factory()->create(['role' => 'admin_hq']);
    $contract = createWorkflowMailContract($negeri, RentalContract::WORKFLOW_DRAF_PERJANJIAN_LULUS);

    $this->actingAs($negeri)
        ->post(route('status-permohonan.return-hq', $contract), [
            'draf_akhir_diterima_acknowledged' => '1',
            'dokumen_perjanjian_disediakan_acknowledged' => '1',
            'dokumen_perjanjian_ditandatangani_acknowledged' => '1',
            'dokumen_asal_dihantar_acknowledged' => '1',
        ])
        ->assertRedirect();

    assertStageMailSentToHqAndNegeri($hq, $negeri, 'Pengesahan & Tandatangan', 'Peringkat 6 — Pengesahan');
});

test('hq finalize emails both roles for mati setem stage', function () {
    Mail::fake();

    $negeri = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Melaka']);
    $hq = User::factory()->create(['role' => 'admin_hq']);
    $contract = createWorkflowMailContract($negeri, RentalContract::WORKFLOW_DRAF_DIKEMBALIKAN_HQ);

    $this->actingAs($hq)
        ->post(route('status-permohonan.finalize', $contract), [
            'terima_perjanjian_pemilik_acknowledged' => '1',
            'dihantar_pkp_saksi_acknowledged' => '1',
            'dihantar_tkpp_penyewa_acknowledged' => '1',
            'dikembalikan_negeri_mati_setem_acknowledged' => '1',
        ])
        ->assertRedirect();

    assertStageMailSentToHqAndNegeri($hq, $negeri, 'Mati Setem', 'Peringkat 7 — Mati Setem (Menunggu Mati Setem)');
});

test('hq final confirmation emails both roles for selesai stage', function () {
    Mail::fake();

    $negeri = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Melaka']);
    $hq = User::factory()->create(['role' => 'admin_hq']);
    $contract = createWorkflowMailContract($negeri, RentalContract::WORKFLOW_MATI_SETEM);

    $this->actingAs($negeri)
        ->post(route('status-permohonan.complete-mati-setem', $contract), [
            'mati_setem_lhdn_acknowledged' => '1',
            'edaran_pemilik_acknowledged' => '1',
            'salinan_dihantar_cawangan_pembangunan_acknowledged' => '1',
        ])
        ->assertRedirect();

    assertStageMailSentToHqAndNegeri($hq, $negeri, 'Menunggu Pengesahan Ibu Pejabat', 'Menunggu pengesahan Ibu Pejabat');

    Mail::assertNotSent(ApplicationWorkflowStageNotification::class, function (ApplicationWorkflowStageNotification $mail): bool {
        return str_contains($mail->envelope()->subject, 'Permohonan Selesai');
    });

    $this->actingAs($hq)
        ->post(route('status-permohonan.complete-hq-mati-setem', $contract), [
            'salinan_diterima_dari_negeri' => '1',
            'perjanjian_dimuat_naik_sistem_premis' => '1',
        ])
        ->assertRedirect();

    assertStageMailSentToHqAndNegeri($hq, $negeri, 'Selesai', 'Peringkat 8 — Selesai');
});

test('workflow mail reaches every active negeri admin and the four jawatan mailboxes', function () {
    Mail::fake();
    Storage::fake('local');

    $negeri = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'negeri-johor@example.test',
    ]);
    $colleague = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'rakan-johor@example.test',
    ]);
    $inactive = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'nyahaktif-johor@example.test',
        'is_active' => false,
    ]);
    $otherNegeri = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Melaka',
        'email' => 'negeri-melaka@example.test',
    ]);
    $hq = User::factory()->create(['role' => 'admin_hq', 'email' => 'hq-aliran@example.test']);
    $contract = createWorkflowMailContract($negeri, RentalContract::WORKFLOW_PENYEDIAAN_DRAF_PERJANJIAN);

    $this->actingAs($negeri)
        ->post(route('status-permohonan.upload-draft', $contract), [
            'document' => UploadedFile::fake()->create('draf.pdf', 100, 'application/pdf'),
        ])
        ->assertRedirect();

    assertStageMailSentToHqAndNegeri($hq, $negeri, 'Semakan PUU');

    Mail::assertSent(ApplicationWorkflowStageNotification::class, function (ApplicationWorkflowStageNotification $mail) use ($colleague): bool {
        return $mail->hasTo($colleague->email);
    });

    Mail::assertNotSent(ApplicationWorkflowStageNotification::class, function (ApplicationWorkflowStageNotification $mail) use ($inactive, $otherNegeri): bool {
        return $mail->hasTo($inactive->email) || $mail->hasTo($otherNegeri->email);
    });
});
