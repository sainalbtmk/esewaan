<?php

use App\Models\Premise;
use App\Models\RentalContract;
use App\Models\User;
use App\Support\ApplicationCategories;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

uses(RefreshDatabase::class);

test('web responses include modern security headers', function () {
    $response = $this->get(route('login'));

    $response->assertSuccessful()
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
        ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
        ->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()')
        ->assertHeader('Content-Security-Policy');

    $policy = (string) $response->headers->get('Content-Security-Policy');

    expect($policy)->toContain("script-src 'self' 'nonce-")
        ->and($policy)->not->toContain('unsafe-eval')
        ->and($policy)->not->toContain('cdn.tailwindcss.com')
        ->and($response->getContent())->not->toContain('cdn.tailwindcss.com')
        ->and($response->getContent())->not->toContain('Ingat saya');
});

test('local vite dev server is allowed by the content security policy', function () {
    $hotFile = public_path('hot');
    $original = is_file($hotFile) ? file_get_contents($hotFile) : null;

    file_put_contents($hotFile, "http://127.0.0.1:5173\n");
    $this->app['env'] = 'local';

    try {
        $policy = (string) $this->get(route('login'))->headers->get('Content-Security-Policy');

        expect($policy)->toContain('http://127.0.0.1:5173')
            ->and($policy)->toContain('ws://127.0.0.1:5173')
            ->and($policy)->toContain("script-src 'self' 'nonce-");
    } finally {
        if ($original === null) {
            unlink($hotFile);
        } else {
            file_put_contents($hotFile, $original);
        }
    }
});

test('failed login attempts are logged without storing password', function () {
    Log::spy();

    User::factory()->create([
        'email' => 'hq@esewa.test',
        'password' => Hash::make('password'),
        'role' => 'admin_hq',
    ]);

    $this->from(route('login'))
        ->post(route('login.store'), [
            'email' => 'hq@esewa.test',
            'password' => 'salah-password',
        ])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');

    Log::shouldHaveReceived('warning')
        ->withArgs(function (string $message, array $context): bool {
            return $message === 'login_failed'
                && ($context['email'] ?? null) === 'hq@esewa.test'
                && ! array_key_exists('password', $context);
        })
        ->once();
});

test('weak password is rejected when registering a user', function () {
    $admin = User::factory()->create(['role' => 'admin_hq']);

    $this->actingAs($admin)
        ->from(route('users.create'))
        ->post(route('users.store'), [
            'name' => 'Pengguna Lemah',
            'email' => 'lemah@esewa.test',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin_negeri',
            'negeri' => 'Johor',
        ])
        ->assertRedirect(route('users.create'))
        ->assertSessionHasErrors('password');
});

test('wrong negeri cannot view kontrak sewaan detail', function () {
    $admin = User::factory()->create(['role' => 'admin_negeri', 'negeri' => 'Selangor']);

    $premise = Premise::query()->create([
        'nama_ptj' => 'Premis Johor',
        'negeri' => 'Johor',
        'daerah' => 'Johor',
        'alamat_penuh' => 'Alamat',
        'nama_pemilik' => 'Pemilik',
    ]);

    $contract = RentalContract::query()->create([
        'premise_id' => $premise->id,
        'tarikh_mula' => RentalContract::PLACEHOLDER_CONTRACT_DATE,
        'tarikh_tamat' => RentalContract::PLACEHOLDER_CONTRACT_DATE,
        'kadar_sewa_bulanan' => 0,
        'status_aktif' => 'aktif',
        'workflow_tahap' => RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
        'kategori_permohonan' => ApplicationCategories::BARU,
        'hq_approved_at' => now(),
        'sah_sehingga' => now()->addMonths(6),
    ]);

    $this->actingAs($admin)
        ->get(route('kontrak-sewaan.show', $contract))
        ->assertNotFound();
});

test('repeated failed logins lock the account even from a new address', function () {
    $user = User::factory()->create([
        'email' => 'kunci@esewa.test',
        'password' => Hash::make('password'),
        'role' => 'admin_hq',
    ]);

    foreach (range(1, 5) as $attempt) {
        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'salah-password',
        ])->assertSessionHasErrors('email');
    }

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('workflow state is not mass assignable', function () {
    RentalContract::allowStateAssignment();

    $premise = Premise::query()->create([
        'nama_ptj' => 'Premis Negeri',
        'negeri' => 'Johor',
        'daerah' => 'Johor',
        'alamat_penuh' => 'Alamat',
        'nama_pemilik' => 'Pemilik',
    ]);

    $contract = RentalContract::query()->create([
        'premise_id' => $premise->id,
        'tarikh_mula' => RentalContract::PLACEHOLDER_CONTRACT_DATE,
        'tarikh_tamat' => RentalContract::PLACEHOLDER_CONTRACT_DATE,
        'kadar_sewa_bulanan' => 0,
        'status_aktif' => 'dalam_proses',
        'workflow_tahap' => RentalContract::WORKFLOW_MENUNGGU_PROCEED_NEGERI,
        'kategori_permohonan' => ApplicationCategories::BARU,
    ]);

    RentalContract::preventStateAssignment();

    $contract->update([
        'workflow_tahap' => RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ,
        'hq_approved_at' => now(),
    ]);

    expect($contract->fresh()->workflow_tahap)->toBe(RentalContract::WORKFLOW_MENUNGGU_PROCEED_NEGERI)
        ->and($contract->fresh()->hq_approved_at)->toBeNull();

    $contract->commit([
        'workflow_tahap' => RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ,
    ]);

    expect($contract->fresh()->workflow_tahap)->toBe(RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ);
});

test('profile email change requires the current password', function () {
    $user = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'profil@esewa.test',
    ]);

    $this->actingAs($user)
        ->from(route('profile.edit'))
        ->put(route('profile.update'), [
            'name' => $user->name,
            'email' => 'baharu@esewa.test',
        ])
        ->assertRedirect(route('profile.edit'))
        ->assertSessionHasErrors('current_password');

    expect($user->fresh()->email)->toBe('profil@esewa.test');
});

test('password change drops other database sessions', function () {
    $user = User::factory()->create([
        'role' => 'admin_negeri',
        'negeri' => 'Johor',
        'email' => 'sesi@esewa.test',
    ]);

    $originalToken = $user->remember_token;

    \Illuminate\Support\Facades\DB::table('sessions')->insert([
        'id' => 'sesi-lain',
        'user_id' => $user->id,
        'payload' => base64_encode('ujian'),
        'last_activity' => time(),
    ]);

    config(['session.driver' => 'database']);

    $this->actingAs($user)
        ->put(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'current_password' => 'password',
            'password' => 'Katalaluan2',
            'password_confirmation' => 'Katalaluan2',
        ])
        ->assertRedirect(route('profile.show'));

    expect(\Illuminate\Support\Facades\DB::table('sessions')->where('id', 'sesi-lain')->exists())->toBeFalse()
        ->and($user->fresh()->remember_token)->not->toBe($originalToken);
});

test('inactive hq account does not keep the last active hq removable', function () {
    $this->withoutMiddleware(\App\Http\Middleware\EnsureUserIsActive::class);

    $actor = User::factory()->create([
        'role' => 'admin_hq',
        'is_active' => false,
        'email' => 'pelakon@esewa.test',
    ]);
    $target = User::factory()->create([
        'role' => 'admin_hq',
        'is_active' => true,
        'email' => 'terakhir@esewa.test',
    ]);
    User::factory()->create([
        'role' => 'admin_hq',
        'is_active' => false,
        'email' => 'tidak-aktif@esewa.test',
    ]);

    $this->actingAs($actor)
        ->from(route('users.edit', $target))
        ->put(route('users.update', $target), [
            'name' => $target->name,
            'email' => $target->email,
            'role' => 'admin_negeri',
            'negeri' => 'Johor',
        ])
        ->assertRedirect(route('users.edit', $target))
        ->assertSessionHasErrors('role');

    expect($target->fresh()->role)->toBe('admin_hq');
});

test('password reset link is not mailed when the log mailer is blocked', function () {
    \Illuminate\Support\Facades\Mail::fake();
    config(['mail.block_password_reset_log' => true]);

    $user = User::factory()->create([
        'email' => 'log-mel@esewa.test',
        'is_active' => true,
    ]);

    $this->post(route('password.email'), [
        'email' => $user->email,
    ])->assertSessionHas('success');

    \Illuminate\Support\Facades\Mail::assertNothingSent();
});
