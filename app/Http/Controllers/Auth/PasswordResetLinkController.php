<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(ForgotPasswordRequest $request): RedirectResponse
    {
        $email = $request->validated('email');
        $user = User::query()->where('email', $email)->first();

        if ($user?->isActive()) {
            Password::sendResetLink(['email' => $email]);

            ActivityLogger::log(
                $user,
                'password_reset_requested',
                'Pautan set semula kata laluan diminta.',
                performedBy: $user,
            );
        }

        return back()->with(
            'success',
            'Jika emel berdaftar dan aktif, pautan set semula kata laluan telah dihantar ke peti masuk anda.',
        );
    }
}
