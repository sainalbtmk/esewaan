<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response as LaravelResponse;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $nonce = str_replace(['+', '/', '='], '', base64_encode(random_bytes(16)));
        $request->attributes->set('csp_nonce', $nonce);

        $response = $next($request);

        if ($response instanceof LaravelResponse) {
            $this->applyScriptNonce($response, $nonce);
        }

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $response->headers->set('Content-Security-Policy', $this->contentSecurityPolicy($nonce));

        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }

    private function applyScriptNonce(LaravelResponse $response, string $nonce): void
    {
        $contentType = (string) $response->headers->get('Content-Type', '');

        if ($contentType !== '' && ! str_contains($contentType, 'text/html')) {
            return;
        }

        $content = $response->getContent();

        if (! is_string($content) || ! str_contains($content, '<script')) {
            return;
        }

        $rewritten = preg_replace(
            '/<script\b(?![^>]*\bnonce=)/i',
            '<script nonce="'.$nonce.'"',
            $content,
        );

        if (! is_string($rewritten) || $rewritten === $content) {
            return;
        }

        $original = $response->original;
        $response->setContent($rewritten);
        $response->original = $original;
    }

    private function contentSecurityPolicy(string $nonce): string
    {
        $styleSrc = "'self' 'unsafe-inline' https://fonts.bunny.net";
        $scriptSrc = "'self' 'nonce-{$nonce}'";
        $connectSrc = "'self'";
        $viteOrigin = $this->localViteOrigin();

        if ($viteOrigin !== null) {
            $styleSrc .= ' '.$viteOrigin;
            $scriptSrc .= ' '.$viteOrigin;
            $connectSrc .= ' '.$viteOrigin;
            $websocket = preg_replace('#^http#', 'ws', $viteOrigin);
            if (is_string($websocket) && $websocket !== $viteOrigin) {
                $connectSrc .= ' '.$websocket;
            }
        }

        return implode('; ', [
            "default-src 'self'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
            "object-src 'none'",
            "img-src 'self' data: blob:",
            "font-src 'self' https://fonts.bunny.net data:",
            "style-src {$styleSrc}",
            "script-src {$scriptSrc}",
            "connect-src {$connectSrc}",
        ]);
    }

    private function localViteOrigin(): ?string
    {
        if (! app()->environment('local')) {
            return null;
        }

        $hotFile = public_path('hot');

        if (! is_file($hotFile)) {
            return null;
        }

        $origin = trim((string) file_get_contents($hotFile));

        return filter_var($origin, FILTER_VALIDATE_URL) ? $origin : null;
    }
}
