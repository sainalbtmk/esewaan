<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Support\MalaysianStates;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $this->ensureLoginIsNotLocked($request);

        if (! Auth::attempt($request->only('email', 'password'), false)) {
            Log::warning('login_failed', [
                'email' => strtolower((string) $request->input('email')),
                'ip' => $request->ip(),
            ]);

            RateLimiter::hit($this->loginLockKey($request), 15 * 60);

            if (RateLimiter::tooManyAttempts($this->loginLockKey($request), 5)) {
                throw ValidationException::withMessages([
                    'email' => [$this->loginLockMessage($request)],
                ]);
            }

            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        if (! $request->user()?->isActive()) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => ['Akaun anda telah dinyahaktifkan. Sila hubungi pentadbir.'],
            ]);
        }

        $user = $request->user();
        RateLimiter::clear($this->loginLockKey($request));

        if (config('auth.mfa.required') && $user->hasConfirmedTwoFactor()) {
            Auth::logout();
            $request->session()->put('login.mfa_user_id', $user->id);
            $request->session()->regenerate();

            return redirect()->route('two-factor.login');
        }

        $request->session()->regenerate();

        ActivityLogger::log(
            $user,
            'login',
            'Pengguna log masuk ke sistem.',
        );

        if (config('auth.mfa.required')) {
            return redirect()->route('two-factor.setup');
        }

        return redirect($user->homeRoute());
    }

    public function showRegisterForm(): View
    {
        $admin = auth()->user();

        return view('auth.register', [
            'negeriList' => MalaysianStates::all(),
            'lockNegeri' => $admin?->isAdminNegeri() ?? false,
            'lockedNegeri' => $admin?->negeri,
        ]);
    }

    public function storeUser(RegisterRequest $request): RedirectResponse
    {
        $user = new User([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
            'negeri' => $request->resolvedNegeri(),
        ]);
        $user->forceFill([
            'role' => $request->validated('role'),
            'is_active' => true,
        ])->save();

        ActivityLogger::log(
            $user,
            'user_created',
            'Akaun didaftarkan oleh pentadbir.',
            ['role' => $user->role],
            $request->user(),
        );

        return redirect()->route('users.index')->with('success', 'Pengguna berjaya didaftarkan.');
    }

    public function logout(Request $request): RedirectResponse
    {
        if ($request->user()) {
            ActivityLogger::log(
                $request->user(),
                'logout',
                'Pengguna log keluar dari sistem.',
            );
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function ensureLoginIsNotLocked(LoginRequest $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->loginLockKey($request), 5)) {
            return;
        }

        throw ValidationException::withMessages([
            'email' => [$this->loginLockMessage($request)],
        ]);
    }

    private function loginLockKey(LoginRequest $request): string
    {
        return 'login-lock:'.strtolower((string) $request->input('email'));
    }

    private function loginLockMessage(LoginRequest $request): string
    {
        $seconds = RateLimiter::availableIn($this->loginLockKey($request));

        return 'Terlalu banyak percubaan gagal. Sila cuba lagi dalam '.$seconds.' saat.';
    }
}
