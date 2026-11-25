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

beforeEach(function () {
    config(['mail.test_recipient' => null]);
});

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
        Mail::assertNotSent(ApplicationWorkflowStageNotification::class, function (ApplicationWorkflowStageNotification $mail) use ($email): bool {
            return $mail->hasTo($email);
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

    assertStageMailSentToHqAndNegeri($hq, $negeri, 'PENASIHAT UNDANG-UNDANG', 'semakan PUU 2');

    Mail::assertSent(ApplicationWorkflowStageNotification::class, function (ApplicationWorkflowStageNotification $mail) use ($hq): bool {
        $html = $mail->render();

        return $mail->hasTo($hq->email)
            && $mail->envelope()->subject === 'SPEKTRA: DRAF PERJANJIAN UNTUK SEMAKAN PENASIHAT UNDANG-UNDANG'
            && str_contains($html, 'telah dimuat naik dan dihantar untuk semakan PUU 2')
            && ! str_contains($html, 'berjaya dimuat naik')
            && str_contains($html, 'https://spektra.aadk.gov.my/');
    });

    Mail::assertSent(ApplicationWorkflowStageNotification::class, function (ApplicationWorkflowStageNotification $mail) use ($negeri): bool {
        $html = $mail->render();

        return $mail->hasTo($negeri->email)
            && $mail->envelope()->subject === 'SPEKTRA: DRAF PERJANJIAN DIHANTAR UNTUK SEMAKAN PENASIHAT UNDANG-UNDANG'
            && str_contains($html, 'berjaya dimuat naik')
            && str_contains($html, 'semakan lanjut')
            && str_contains($html, 'dikemukakan oleh '.$negeri->name)
            && ! str_contains($html, 'dikemukakan oleh '.$negeri->id);
    });
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

    assertStageMailSentToHqAndNegeri($hq, $negeri, 'KUIRI OLEH PENASIHAT UNDANG-UNDANG', 'Semak semula');

    Mail::assertSent(ApplicationWorkflowStageNotification::class, function (ApplicationWorkflowStageNotification $mail) use ($negeri): bool {
        return $mail->hasTo($negeri->email)
            && str_contains($mail->render(), 'tujuh (7) hari bekerja');
    });

    Mail::assertSent(ApplicationWorkflowStageNotification::class, function (ApplicationWorkflowStageNotification $mail) use ($hq): bool {
        return $mail->hasTo($hq->email)
            && ! str_contains($mail->render(), 'tujuh (7) hari bekerja');
    });
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

    assertStageMailSentToHqAndNegeri($hq, $negeri, 'DILULUSKAN OLEH PENASIHAT UNDANG-UNDANG', 'tertakluk kepada pindaan');

    Mail::assertSent(ApplicationWorkflowStageNotification::class, function (ApplicationWorkflowStageNotification $mail) use ($negeri): bool {
        return $mail->hasTo($negeri->email)
            && str_contains($mail->render(), 'tiga (3) salinan');
    });

    Mail::assertSent(ApplicationWorkflowStageNotification::class, function (ApplicationWorkflowStageNotification $mail) use ($hq): bool {
        return $mail->hasTo($hq->email)
            && ! str_contains($mail->render(), 'tiga (3) salinan');
    });
});

test('completing pindaan emails hq and negeri for draf lulus stage', function () {
    Mail::fake();

    $negeri = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Melaka']);
    $hq = User::factory()->create(['role' => 'admin_hq']);
    $contract = createWorkflowMailContract($negeri, RentalContract::WORKFLOW_PINDAAN_BERDASARKAN_PUU);

    $this->actingAs($hq)
        ->post(route('status-permohonan.complete', $contract))
        ->assertRedirect();

    assertStageMailSentToHqAndNegeri($hq, $negeri, 'DILULUSKAN OLEH PENASIHAT UNDANG-UNDANG', 'tertakluk kepada pindaan');

    Mail::assertSent(ApplicationWorkflowStageNotification::class, function (ApplicationWorkflowStageNotification $mail) use ($negeri): bool {
        return $mail->hasTo($negeri->email)
            && str_contains($mail->render(), 'tiga (3) salinan');
    });
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

    assertStageMailSentToHqAndNegeri($hq, $negeri, 'DITANDATANGANI PENGURUSAN AADK', 'selaku Penyewa');
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

    assertStageMailSentToHqAndNegeri($hq, $negeri, 'TINDAKAN MATI SETEM', 'Lembaga Hasil Dalam Negeri');
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

    Mail::assertSent(ApplicationWorkflowStageNotification::class, function (ApplicationWorkflowStageNotification $mail) use ($hq): bool {
        return $mail->hasTo($hq->email)
            && $mail->envelope()->subject === 'SPEKTRA: PERJANJIAN SELESAI DIMATI SETEM'
            && str_contains($mail->render(), 'pengesahan penerimaan Perjanjian di peringkat Ibu Pejabat');
    });

    Mail::assertNotSent(ApplicationWorkflowStageNotification::class, function (ApplicationWorkflowStageNotification $mail) use ($negeri): bool {
        return $mail->hasTo($negeri->email);
    });

    foreach (EmelJawatan::query()->orderBy('susunan')->pluck('email') as $email) {
        Mail::assertNotSent(ApplicationWorkflowStageNotification::class, function (ApplicationWorkflowStageNotification $mail) use ($email): bool {
            return $mail->hasTo($email);
        });
    }

    Mail::assertNotSent(ApplicationWorkflowStageNotification::class, function (ApplicationWorkflowStageNotification $mail): bool {
        return str_contains($mail->envelope()->subject, 'SENARAI KONTRAK SEWAAN');
    });

    $this->actingAs($hq)
        ->post(route('status-permohonan.complete-hq-mati-setem', $contract), [
            'salinan_diterima_dari_negeri' => '1',
            'perjanjian_dimuat_naik_sistem_premis' => '1',
        ])
        ->assertRedirect();

    Mail::assertSent(ApplicationWorkflowStageNotification::class, function (ApplicationWorkflowStageNotification $mail) use ($negeri): bool {
        $html = $mail->render();

        return $mail->hasTo($negeri->email)
            && $mail->envelope()->subject === 'SPEKTRA: PERJANJIAN SELESAI DAN TELAH DIMASUKKAN KE DALAM SENARAI KONTRAK SEWAAN'
            && str_contains($html, 'Sistem ProMIS')
            && str_contains($html, 'Senarai Kontrak Sewaan');
    });

    Mail::assertNotSent(ApplicationWorkflowStageNotification::class, function (ApplicationWorkflowStageNotification $mail) use ($hq): bool {
        return $mail->hasTo($hq->email)
            && str_contains($mail->envelope()->subject, 'SENARAI KONTRAK SEWAAN');
    });
});

test('workflow mail reaches every active negeri admin and skips the jawatan mailboxes', function () {
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

    assertStageMailSentToHqAndNegeri($hq, $negeri, 'PENASIHAT UNDANG-UNDANG');

    Mail::assertSent(ApplicationWorkflowStageNotification::class, function (ApplicationWorkflowStageNotification $mail) use ($colleague): bool {
        return $mail->hasTo($colleague->email);
    });

    Mail::assertNotSent(ApplicationWorkflowStageNotification::class, function (ApplicationWorkflowStageNotification $mail) use ($inactive, $otherNegeri): bool {
        return $mail->hasTo($inactive->email) || $mail->hasTo($otherNegeri->email);
    });
});
