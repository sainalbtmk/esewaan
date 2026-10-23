<?php

use App\Models\ContractDocument;
use App\Models\Premise;
use App\Models\RentalContract;
use App\Models\User;
use App\Support\ApplicationCategories;
use App\Support\ContractDocumentStorage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

function createDocumentContract(string $negeri = 'Johor'): RentalContract
{
    $premise = Premise::query()->create([
        'nama_ptj' => 'Premis Dokumen',
        'negeri' => $negeri,
        'daerah' => $negeri,
        'alamat_penuh' => 'Alamat ujian',
        'nama_pemilik' => 'Pemilik',
    ]);

    return RentalContract::query()->create([
        'premise_id' => $premise->id,
        'tarikh_mula' => RentalContract::PLACEHOLDER_CONTRACT_DATE,
        'tarikh_tamat' => RentalContract::PLACEHOLDER_CONTRACT_DATE,
        'kadar_sewa_bulanan' => 0,
        'status_aktif' => 'dalam_proses',
        'workflow_tahap' => RentalContract::WORKFLOW_MENUNGGU_PROCEED_NEGERI,
        'kategori_permohonan' => ApplicationCategories::BARU,
    ]);
}

test('guest cannot download contract document', function () {
    Storage::fake(ContractDocumentStorage::DISK);

    $contract = createDocumentContract();
    $path = 'contract-documents/'.$contract->id.'/draf.pdf';
    Storage::disk(ContractDocumentStorage::DISK)->put($path, '%PDF-1.4 test');

    $document = ContractDocument::query()->create([
        'contract_id' => $contract->id,
        'nama_fail' => 'draf.pdf',
        'path' => $path,
        'jenis' => ContractDocument::JENIS_DRAF_PERJANJIAN,
        'user_id' => User::factory()->create()->id,
    ]);

    $this->get(route('documents.show', [$contract, $document]))
        ->assertRedirect(route('login'));
});

test('admin negeri from another state cannot download document', function () {
    Storage::fake(ContractDocumentStorage::DISK);

    $owner = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Johor']);
    $other = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Selangor']);
    $contract = createDocumentContract('Johor');
    $path = 'contract-documents/'.$contract->id.'/draf.pdf';
    Storage::disk(ContractDocumentStorage::DISK)->put($path, '%PDF-1.4 test');

    $document = ContractDocument::query()->create([
        'contract_id' => $contract->id,
        'nama_fail' => 'draf.pdf',
        'path' => $path,
        'jenis' => ContractDocument::JENIS_DRAF_PERJANJIAN,
        'user_id' => $owner->id,
    ]);

    $this->actingAs($other)
        ->get(route('documents.show', [$contract, $document]))
        ->assertNotFound();
});

test('admin negeri can download own state document from private disk', function () {
    Storage::fake(ContractDocumentStorage::DISK);

    $admin = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Johor']);
    $contract = createDocumentContract('Johor');
    $path = 'contract-documents/'.$contract->id.'/draf.pdf';
    Storage::disk(ContractDocumentStorage::DISK)->put($path, '%PDF-1.4 test');

    $document = ContractDocument::query()->create([
        'contract_id' => $contract->id,
        'nama_fail' => 'draf.pdf',
        'path' => $path,
        'jenis' => ContractDocument::JENIS_DRAF_PERJANJIAN,
        'user_id' => $admin->id,
    ]);

    $this->actingAs($admin)
        ->get(route('documents.show', [$contract, $document]))
        ->assertSuccessful()
        ->assertHeader('content-type', 'application/pdf');

    expect($this->actingAs($admin)->get(route('documents.show', [$contract, $document]))->headers->get('content-disposition'))
        ->toContain('attachment');
});

test('document upload stores file on private local disk', function () {
    Storage::fake(ContractDocumentStorage::DISK);

    $admin = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Johor']);
    $contract = createDocumentContract('Johor');

    $this->actingAs($admin)
        ->post(route('documents.store', $contract), [
            'document' => UploadedFile::fake()->create('surat.pdf', 100, 'application/pdf'),
            'jenis' => 'surat_tawaran',
        ])
        ->assertRedirect(route('status-permohonan.index'))
        ->assertSessionHas('success');

    $document = $contract->fresh()->documents()->first();

    expect($document)->not->toBeNull()
        ->and(Storage::disk(ContractDocumentStorage::DISK)->exists($document->path))->toBeTrue()
        ->and(Storage::disk('public')->exists($document->path))->toBeFalse();
});

test('admin negeri from another state cannot open document upload page', function () {
    $admin = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Selangor']);
    $contract = createDocumentContract('Johor');

    $this->actingAs($admin)
        ->get(route('documents.create', $contract))
        ->assertNotFound();
});
