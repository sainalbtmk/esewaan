<?php

use App\Models\ActivityLog;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Support\Totp;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

test('admin ibu pejabat can view every user activity in the audit log', function () {
    $this->travelTo(Carbon::parse('2026-11-23 08:15:42', 'Asia/Kuala_Lumpur'));

    $admin = User::factory()->create(['role' => 'admin_hq', 'name' => 'Admin Ibu Pejabat']);
    $actor = User::factory()->create(['role' => 'admin_negeri', 'name' => 'Siti Aminah', 'negeri' => 'Johor']);

    ActivityLogger::log(
        $actor,
        'login',
        'Pengguna log masuk ke sistem.',
        performedBy: $actor,
    );

    $this->actingAs($admin)
        ->get(route('users.audit-log'))
        ->assertSuccessful()
        ->assertSee('Log Audit')
        ->assertSee('Siti Aminah')
        ->assertSee('23/11/2026 08:15:42')
        ->assertDontSee('23/11/2026 00:15:42')
        ->assertSee('Cap Masa')
        ->assertSee('Cetak PDF')
        ->assertSee('Log masuk')
        ->assertSee('Pengguna log masuk ke sistem.')
        ->assertSee('Rekod tidak boleh dikemaskini atau dipadam.')
        ->assertDontSee('Urus profil');
});

test('audit log keeps the name recorded at the time of the activity', function () {
    $admin = User::factory()->create(['role' => 'admin_hq', 'name' => 'Admin Ibu Pejabat']);
    $actor = User::factory()->create(['role' => 'admin_negeri', 'name' => 'Nama Asal', 'negeri' => 'Johor']);

    ActivityLogger::log(
        $actor,
        'logout',
        'Pengguna log keluar dari sistem.',
        performedBy: $actor,
    );

    $actor->update(['name' => 'Nama Ditukar']);

    $this->actingAs($admin)
        ->get(route('users.audit-log'))
        ->assertSuccessful()
        ->assertSee('Nama Asal')
        ->assertDontSee('Nama Ditukar');
});

test('audit log shows the actor and the user affected by the activity', function () {
    $admin = User::factory()->create(['role' => 'admin_hq', 'name' => 'Admin Ibu Pejabat']);
    $target = User::factory()->create(['role' => 'admin_negeri', 'name' => 'Pengguna Sasaran', 'negeri' => 'Johor']);

    ActivityLogger::log(
        $target,
        'profile_updated',
        'Profil dikemaskini oleh pentadbir.',
        performedBy: $admin,
    );

    $this->actingAs($admin)
        ->get(route('users.audit-log'))
        ->assertSuccessful()
        ->assertSee('Admin Ibu Pejabat')
        ->assertSee('Kemaskini profil')
        ->assertSee('Terhadap: Pengguna Sasaran');
});

test('audit log search matches name and activity', function () {
    $admin = User::factory()->create(['role' => 'admin_hq', 'name' => 'Admin Ibu Pejabat']);
    $ahmad = User::factory()->create(['role' => 'admin_negeri', 'name' => 'Ahmad Zainal', 'negeri' => 'Johor']);
    $siti = User::factory()->create(['role' => 'admin_negeri', 'name' => 'Siti Aminah', 'negeri' => 'Johor']);

    ActivityLogger::log($ahmad, 'login', 'Pengguna log masuk ke sistem.', performedBy: $ahmad);
    ActivityLogger::log($siti, 'logout', 'Pengguna log keluar dari sistem.', performedBy: $siti);

    $this->actingAs($admin)
        ->get(route('users.audit-log', ['search' => 'Ahmad']))
        ->assertSuccessful()
        ->assertSee('Ahmad Zainal')
        ->assertDontSee('Siti Aminah');

    $this->actingAs($admin)
        ->get(route('users.audit-log', ['search' => 'Log keluar']))
        ->assertSuccessful()
        ->assertSee('Siti Aminah')
        ->assertDontSee('Ahmad Zainal');
});

test('audit log paginates activities from all users', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);
    $actor = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Johor']);

    foreach (range(1, 16) as $number) {
        ActivityLogger::log(
            $actor,
            'login',
            'Log masuk nombor '.$number,
            performedBy: $actor,
        );
    }

    $this->actingAs($admin)
        ->get(route('users.audit-log', ['page' => 2]))
        ->assertSuccessful()
        ->assertSee('Log masuk nombor 1')
        ->assertDontSee('Log masuk nombor 16');
});

test('audit log menu is visible only to admin ibu pejabat', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);
    $negeri = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Johor']);

    $this->actingAs($admin)
        ->get(route('users.index'))
        ->assertSuccessful()
        ->assertSee('Log Audit')
        ->assertSee(route('users.audit-log'));

    $this->actingAs($negeri)
        ->get(route('users.index'))
        ->assertSuccessful()
        ->assertDontSee('Log Audit')
        ->assertDontSee(route('users.audit-log'));
});

test('admin negeri cannot open the audit log or a user audit trail', function () {
    $negeri = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Johor']);
    $other = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Johor']);

    $this->actingAs($negeri)
        ->get(route('users.audit-log'))
        ->assertForbidden();

    $this->actingAs($negeri)
        ->get(route('users.audit-trail', $other))
        ->assertForbidden();
});

test('guests are redirected to login from the audit log', function () {
    $this->get(route('users.audit-log'))
        ->assertRedirect(route('login'));
});

test('audit log has no update or delete route', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);

    $this->actingAs($admin)
        ->put(route('users.audit-log'), ['description' => 'diubah'])
        ->assertNotFound();

    $this->actingAs($admin)
        ->delete(route('users.audit-log'))
        ->assertMethodNotAllowed();
});

test('audit records cannot be updated or deleted', function () {
    $user = User::factory()->create();
    $log = ActivityLogger::log($user, 'login', 'Pengguna log masuk ke sistem.', performedBy: $user);

    expect(fn () => $log->update(['description' => 'diubah']))
        ->toThrow(RuntimeException::class, ActivityLog::IMMUTABLE_MESSAGE);

    expect(fn () => $log->delete())
        ->toThrow(RuntimeException::class, ActivityLog::IMMUTABLE_MESSAGE);

    expect(fn () => ActivityLog::query()->whereKey($log->id)->update(['description' => 'diubah']))
        ->toThrow(RuntimeException::class, ActivityLog::IMMUTABLE_MESSAGE);

    expect(fn () => ActivityLog::query()->whereKey($log->id)->delete())
        ->toThrow(RuntimeException::class, ActivityLog::IMMUTABLE_MESSAGE);

    expect(fn () => DB::table('log_aktiviti')->where('id', $log->id)->update(['description' => 'diubah']))
        ->toThrow(QueryException::class);

    expect(fn () => DB::table('log_aktiviti')->where('id', $log->id)->delete())
        ->toThrow(QueryException::class);

    $this->assertDatabaseHas('log_aktiviti', [
        'id' => $log->id,
        'description' => 'Pengguna log masuk ke sistem.',
    ]);
});

test('successful and failed logins are written to the audit log', function () {
    $user = User::factory()->create([
        'role' => 'admin_negeri',
        'name' => 'Pengguna Jejak',
        'email' => 'jejak@example.test',
        'negeri' => 'Johor',
    ]);

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'salah',
    ])->assertSessionHasErrors('email');

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect();

    $admin = User::factory()->create(['role' => 'admin_hq']);

    $this->actingAs($admin)
        ->get(route('users.audit-log'))
        ->assertSuccessful()
        ->assertSee('Pengguna Jejak')
        ->assertSee('Log masuk gagal')
        ->assertSee('Pengguna log masuk ke sistem.')
        ->assertSee('Percubaan log masuk gagal.');
});

test('blocked login, password reset, two factor, and report download are audited', function () {
    Mail::fake();

    $inactive = User::factory()->create([
        'name' => 'Akaun Nyahaktif',
        'email' => 'nyahaktif-audit@example.test',
        'is_active' => false,
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
    ]);

    $this->post(route('login.store'), [
        'email' => $inactive->email,
        'password' => 'password',
    ])->assertSessionHasErrors('email');

    $active = User::factory()->create([
        'name' => 'Pemohon Set Semula',
        'email' => 'set-semula-audit@example.test',
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
    ]);

    $this->post(route('password.email'), [
        'email' => $active->email,
    ])->assertRedirect();

    config(['auth.mfa.required' => true]);

    $hq = User::factory()->create([
        'name' => 'Pentadbir Dua Faktor',
        'email' => 'mfa-audit@example.test',
        'role' => 'admin_hq',
    ]);

    $this->post(route('login.store'), [
        'email' => $hq->email,
        'password' => 'password',
    ])->assertRedirect(route('two-factor.setup'));

    $this->get(route('two-factor.setup'))->assertSuccessful();

    $this->post(route('two-factor.confirm'), [
        'code' => Totp::code((string) $hq->fresh()->two_factor_secret),
    ])->assertRedirect(route('two-factor.recovery'));

    $this->get(route('reports.pdf'))->assertSuccessful();

    config(['auth.mfa.required' => false]);

    $viewer = User::factory()->create(['role' => 'admin_hq', 'name' => 'Pemerhati Audit']);

    $this->actingAs($viewer)
        ->get(route('users.audit-log'))
        ->assertSuccessful()
        ->assertSee('Akaun Nyahaktif')
        ->assertSee('Log masuk disekat')
        ->assertSee('Pemohon Set Semula')
        ->assertSee('Permintaan set semula kata laluan')
        ->assertSee('Pentadbir Dua Faktor')
        ->assertSee('Pengesahan dua faktor diaktifkan')
        ->assertSee('Laporan dimuat turun');
});

test('admin ibu pejabat can print the audit log for a date range as pdf', function () {
    $admin = User::factory()->create(['role' => 'admin_hq', 'name' => 'Admin Cetak']);
    $actor = User::factory()->create(['role' => 'admin_negeri', 'name' => 'Pengguna Cetak', 'negeri' => 'Johor']);

    $this->travelTo(Carbon::parse('2026-11-23 00:30:00', 'Asia/Kuala_Lumpur'));
    ActivityLogger::log($actor, 'login', 'Aktiviti awal hari.', performedBy: $actor);

    $this->travelTo(Carbon::parse('2026-11-23 23:30:00', 'Asia/Kuala_Lumpur'));
    ActivityLogger::log($actor, 'logout', 'Aktiviti hujung hari.', performedBy: $actor);

    $this->travelTo(Carbon::parse('2026-11-24 00:15:00', 'Asia/Kuala_Lumpur'));
    ActivityLogger::log($actor, 'login', 'Aktiviti hari seterusnya.', performedBy: $actor);

    $matched = ActivityLog::query()
        ->whereStartedBetween(
            Carbon::parse('2026-11-23', 'Asia/Kuala_Lumpur'),
            Carbon::parse('2026-11-23', 'Asia/Kuala_Lumpur'),
        )
        ->pluck('description');

    expect($matched->all())
        ->toContain('Aktiviti awal hari.')
        ->toContain('Aktiviti hujung hari.')
        ->not->toContain('Aktiviti hari seterusnya.');

    $response = $this->actingAs($admin)
        ->get(route('users.audit-log.pdf', [
            'tarikh_mula' => '2026-11-23',
            'tarikh_akhir' => '2026-11-23',
        ]));

    $response->assertSuccessful()
        ->assertHeader('content-type', 'application/pdf')
        ->assertDownload('log-audit-20261123-20261123.pdf');

    expect(pdfContainsText($response->getContent(), 'SULIT'))->toBeTrue()
        ->and(pdfContainsText($response->getContent(), 'Admin Cetak'))->toBeTrue()
        ->and(pdfContainsText($response->getContent(), '24/11/2026 00:15:00'))->toBeTrue();

    expect(ActivityLog::query()->where('action', 'audit_log_printed')->exists())->toBeTrue();
});

test('audit log pdf rejects an end date before the start date', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);

    $this->actingAs($admin)
        ->from(route('users.audit-log'))
        ->get(route('users.audit-log.pdf', [
            'tarikh_mula' => '2026-11-24',
            'tarikh_akhir' => '2026-11-23',
        ]))
        ->assertRedirect(route('users.audit-log'))
        ->assertSessionHasErrors('tarikh_akhir');
});

test('admin negeri cannot print the audit log', function () {
    $negeri = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Johor']);

    $this->actingAs($negeri)
        ->get(route('users.audit-log.pdf', [
            'tarikh_mula' => '2026-11-23',
            'tarikh_akhir' => '2026-11-23',
        ]))
        ->assertForbidden();
});
