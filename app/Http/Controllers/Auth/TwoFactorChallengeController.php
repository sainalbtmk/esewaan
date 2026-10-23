<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChallengeTwoFactorRequest;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Support\Totp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TwoFactorChallengeController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('login.mfa_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge');
    }

    public function store(ChallengeTwoFactorRequest $request): RedirectResponse
    {
        $userId = $request->session()->get('login.mfa_user_id');
        $user = is_numeric($userId) ? User::query()->find($userId) : null;

        if (! $user instanceof User || ! $user->hasConfirmedTwoFactor() || ! $user->isActive()) {
            $request->session()->forget('login.mfa_user_id');

            return redirect()
                ->route('login')
                ->with('error', 'Sesi pengesahan tamat. Sila log masuk semula.');
        }

        $code = (string) $request->validated('code');
        $valid = Totp::verify((string) $user->two_factor_secret, $code)
            || $user->consumeRecoveryCode($code);

        if (! $valid) {
            throw ValidationException::withMessages([
                'code' => ['Kod pengesahan tidak sah.'],
            ]);
        }

        $request->session()->forget('login.mfa_user_id');
        Auth::login($user);
        $request->session()->regenerate();

        ActivityLogger::log(
            $user,
            'login',
            'Pengguna log masuk ke sistem.',
        );

        return redirect($user->homeRoute());
    }
}
