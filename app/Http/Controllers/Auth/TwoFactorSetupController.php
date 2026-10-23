<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConfirmTwoFactorRequest;
use App\Support\Totp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TwoFactorSetupController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->hasConfirmedTwoFactor()) {
            return redirect()->route('dashboard');
        }

        if (! filled($user->two_factor_secret)) {
            $plainCodes = collect(range(1, 8))
                ->map(fn (): string => Str::lower(Str::password(10, letters: true, numbers: true, symbols: false)))
                ->all();

            $user->forceFill([
                'two_factor_secret' => Totp::generateSecret(),
                'two_factor_recovery_codes' => collect($plainCodes)
                    ->map(fn (string $code): string => Hash::make($code))
                    ->all(),
            ])->save();

            $request->session()->put('two_factor.recovery_plain', $plainCodes);
        }

        $user->refresh();

        return view('auth.two-factor-setup', [
            'secret' => (string) $user->two_factor_secret,
            'provisioningUri' => Totp::provisioningUri(
                (string) $user->two_factor_secret,
                $user->email,
                (string) config('app.name'),
            ),
            'recoveryCodes' => $request->session()->get('two_factor.recovery_plain', []),
        ]);
    }

    public function store(ConfirmTwoFactorRequest $request): RedirectResponse
    {
        $user = $request->user();

        if (! filled($user->two_factor_secret) || ! Totp::verify((string) $user->two_factor_secret, (string) $request->validated('code'))) {
            return back()->withErrors([
                'code' => 'Kod pengesahan tidak sah.',
            ]);
        }

        $user->forceFill([
            'two_factor_confirmed_at' => now(),
        ])->save();

        return redirect()->route('two-factor.recovery');
    }

    public function recovery(Request $request): View|RedirectResponse
    {
        /** @var list<string> $codes */
        $codes = $request->session()->pull('two_factor.recovery_plain', []);

        if ($codes === []) {
            return redirect()->route('dashboard');
        }

        return view('auth.two-factor-recovery', [
            'recoveryCodes' => $codes,
        ]);
    }
}
