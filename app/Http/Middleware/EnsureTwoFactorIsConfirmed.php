<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorIsConfirmed
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null || ! config('auth.mfa.required') || $user->hasConfirmedTwoFactor()) {
            return $next($request);
        }

        if ($request->routeIs('two-factor.setup', 'two-factor.confirm', 'two-factor.recovery', 'logout')) {
            return $next($request);
        }

        return redirect()->route('two-factor.setup');
    }
}
