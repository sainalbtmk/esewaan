<?php

use App\Models\User;
use App\Support\Totp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('local and testing logins stay on password only', function () {
    config(['auth.mfa.required' => false]);

    $user = User::factory()->create([
        'email' => 'biasa@esewa.test',
        'password' => Hash::make('password'),
        'role' => 'admin_hq',
    ]);

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('production mfa must be confirmed before the dashboard opens', function () {
    config(['auth.mfa.required' => true]);

    $user = User::factory()->create([
        'email' => 'mfa@esewa.test',
        'password' => Hash::make('password'),
        'role' => 'admin_hq',
    ]);

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('two-factor.setup'));

    $this->get(route('dashboard'))->assertRedirect(route('two-factor.setup'));

    $this->get(route('two-factor.setup'))->assertSuccessful();

    $secret = $user->fresh()->two_factor_secret;
    $recoveryCodes = session('two_factor.recovery_plain');

    expect($secret)->not->toBeEmpty()
        ->and($recoveryCodes)->toHaveCount(8);

    $this->post(route('two-factor.confirm'), [
        'code' => Totp::code($secret),
    ])->assertRedirect(route('two-factor.recovery'));

    $this->get(route('dashboard'))->assertSuccessful();

    $this->post(route('logout'));

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('two-factor.login'));

    $this->get(route('dashboard'))->assertRedirect(route('login'));

    $this->post(route('two-factor.login.store'), [
        'code' => '000000',
    ])->assertSessionHasErrors('code');

    $this->post(route('two-factor.login.store'), [
        'code' => $recoveryCodes[0],
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);

    $this->post(route('logout'));

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->post(route('two-factor.login.store'), [
        'code' => $recoveryCodes[0],
    ])->assertSessionHasErrors('code');
});
