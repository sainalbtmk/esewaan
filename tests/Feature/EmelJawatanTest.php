<?php

use App\Models\ActivityLog;
use App\Models\EmelJawatan;
use App\Models\User;
use App\Services\ContractExpiryReminderMailer;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function emelJawatanPayload(array $overrides = []): array
{
    $emails = EmelJawatan::query()
        ->orderBy('susunan')
        ->pluck('email', 'kod')
        ->all();

    return [
        'emails' => array_merge($emails, $overrides),
    ];
}

test('ibu pejabat sees jawatan names and emails on the user list', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);

    $this->actingAs($admin)
        ->get(route('users.index'))
        ->assertSuccessful()
        ->assertSee('Salinan Emel Jawatan')
        ->assertSee('Nama Jawatan')
        ->assertSee('Ketua Pengarah')
        ->assertSee('TKPP')
        ->assertSee('PKP')
        ->assertSee('KPP')
        ->assertSee('ruslin@aadk.gov.my')
        ->assertSee('shobah@aadk.gov.my')
        ->assertSee('lai@aadk.gov.my')
        ->assertSee('zasmi@aadk.gov.my');
});

test('negeri admins do not see or update the jawatan email list', function () {
    $negeri = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
    ]);

    $this->actingAs($negeri)
        ->get(route('users.index'))
        ->assertSuccessful()
        ->assertDontSee('Salinan Emel Jawatan')
        ->assertDontSee('ruslin@aadk.gov.my');

    $this->actingAs($negeri)
        ->put(route('users.emel-jawatan.update'), emelJawatanPayload([
            'ketua_pengarah' => 'baru@aadk.gov.my',
        ]))
        ->assertForbidden();

    expect(EmelJawatan::query()->where('kod', 'ketua_pengarah')->value('email'))
        ->toBe('ruslin@aadk.gov.my');
});

test('ibu pejabat can replace a jawatan email', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);

    $this->actingAs($admin)
        ->put(route('users.emel-jawatan.update'), emelJawatanPayload([
            'ketua_pengarah' => 'pengarah.baharu@aadk.gov.my',
        ]))
        ->assertRedirect(route('users.index'))
        ->assertSessionHas('success');

    expect(EmelJawatan::query()->where('kod', 'ketua_pengarah')->value('email'))
        ->toBe('pengarah.baharu@aadk.gov.my')
        ->and(EmelJawatan::query()->where('kod', 'tkpp')->value('email'))
        ->toBe('shobah@aadk.gov.my');

    $this->actingAs($admin)
        ->get(route('users.index'))
        ->assertSee('pengarah.baharu@aadk.gov.my')
        ->assertDontSee('ruslin@aadk.gov.my');

    expect(ActivityLog::query()->where('action', 'emel_jawatan_updated')->exists())->toBeTrue();
});

test('ibu pejabat cannot save an invalid jawatan email', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);

    $this->actingAs($admin)
        ->from(route('users.index'))
        ->put(route('users.emel-jawatan.update'), emelJawatanPayload([
            'pkp' => 'bukan-emel',
        ]))
        ->assertRedirect(route('users.index'))
        ->assertSessionHasErrors('emails.pkp');

    expect(EmelJawatan::query()->where('kod', 'pkp')->value('email'))
        ->toBe('lai@aadk.gov.my');
});

test('contract warnings copy the jawatan emails saved by ibu pejabat', function () {
    EmelJawatan::query()->where('kod', 'kpp')->update([
        'email' => 'kpp.baharu@aadk.gov.my',
    ]);

    $copies = app(ContractExpiryReminderMailer::class)->warningCcRecipients([]);

    expect($copies)->toContain('kpp.baharu@aadk.gov.my')
        ->and($copies)->toContain('ruslin@aadk.gov.my')
        ->and($copies)->not->toContain('zasmi@aadk.gov.my');
});
