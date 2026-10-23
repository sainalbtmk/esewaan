<?php

namespace App\Providers;

use App\Services\SidebarNotificationService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
        $this->configurePasswordDefaults();
        $this->configureTrustedProxies();
        $this->configureProductionGuards();

        if ($this->app->environment('local')) {
            $this->app->booted(function (): void {
                if ($this->app->runningInConsole() || ! $this->app->bound('request')) {
                    return;
                }

                $request = $this->app->make('request');

                if ($request->getHost() !== '') {
                    URL::forceRootUrl($request->getSchemeAndHttpHost());
                }
            });
        }

        View::composer(['layouts.app', 'partials.sidebar-*'], function ($view): void {
            $user = auth()->user();

            if (! $user) {
                return;
            }

            $service = app(SidebarNotificationService::class);

            $view->with([
                'sidebarNotifications' => $service->forUser($user),
                'inboxPreviewItems' => $user->isAdmin()
                    ? $service->inboxItems($user, 8)
                    : collect(),
            ]);
        });
    }

    protected function configurePasswordDefaults(): void
    {
        Password::defaults(function () {
            $rule = Password::min(8)->mixedCase()->numbers();

            return $this->app->isProduction()
                ? $rule->uncompromised()
                : $rule;
        });
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $key = strtolower((string) $request->input('email')).'|'.$request->ip();

            return Limit::perMinute(10)->by($key);
        });

        RateLimiter::for('password-reset', function (Request $request) {
            $key = strtolower((string) $request->input('email')).'|'.$request->ip();

            return Limit::perMinute(5)->by($key);
        });

        RateLimiter::for('autosave', function (Request $request) {
            return Limit::perMinute(120)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('form-actions', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('sidebar-notifications', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('status-permohonan-sync', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            $pendingUser = $request->session()->get('login.mfa_user_id');

            return Limit::perMinute(5)->by($pendingUser ?: $request->user()?->id ?: $request->ip());
        });
    }

    protected function configureTrustedProxies(): void
    {
        $trusted = config('app.trusted_proxies');

        if (! is_string($trusted) || $trusted === '') {
            return;
        }

        TrustProxies::at($trusted === '*'
            ? '*'
            : array_values(array_filter(array_map(trim(...), explode(',', $trusted)))));
    }

    protected function configureProductionGuards(): void
    {
        $mfa = config('auth.mfa.required');

        config([
            'auth.mfa.required' => $mfa === null
                ? $this->app->environment('production')
                : filter_var($mfa, FILTER_VALIDATE_BOOLEAN),
            'mail.block_password_reset_log' => $this->app->environment('production') && config('mail.default') === 'log',
        ]);
    }
}
