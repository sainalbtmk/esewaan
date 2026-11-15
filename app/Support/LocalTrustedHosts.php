<?php

namespace App\Support;

class LocalTrustedHosts
{
    /**
     * Host pattern for the production application URL only.
     *
     * @return list<string>
     */
    public static function productionPatterns(): array
    {
        $host = parse_url((string) config('app.url'), PHP_URL_HOST);

        if (! is_string($host) || $host === '') {
            return [];
        }

        $host = strtolower($host);

        if (preg_match('/^[a-z0-9.:-]+$/', $host) !== 1) {
            return [];
        }

        return ['^'.preg_quote($host, '#').'$'];
    }

    /**
     * Host patterns trusted while the application environment is local.
     *
     * @return list<string>
     */
    public static function patterns(): array
    {
        $patterns = [];

        foreach (self::hosts() as $host) {
            $patterns[] = '^'.preg_quote($host, '#').'$';
        }

        return $patterns;
    }

    public static function allows(string $host): bool
    {
        $host = strtolower(trim($host));
        $host = preg_replace('/:\d+$/', '', $host) ?? '';

        if ($host === '' || preg_match('/\s/', $host) === 1) {
            return false;
        }

        foreach (self::patterns() as $pattern) {
            if (preg_match('{'.$pattern.'}i', $host) === 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return list<string>
     */
    private static function hosts(): array
    {
        $hosts = [
            'localhost',
            '127.0.0.1',
            '::1',
            '[::1]',
            'esewaan.test',
        ];

        $appHost = parse_url((string) config('app.url'), PHP_URL_HOST);

        if (is_string($appHost) && $appHost !== '') {
            $hosts[] = $appHost;
        }

        $extra = config('app.allowed_hosts');

        if (is_array($extra)) {
            foreach ($extra as $host) {
                if (is_string($host) && $host !== '') {
                    $hosts[] = $host;
                }
            }
        }

        $normalized = [];

        foreach ($hosts as $host) {
            $host = strtolower(trim($host));

            if ($host === '' || preg_match('/^[a-z0-9.:\[\]-]+$/', $host) !== 1) {
                continue;
            }

            $normalized[$host] = $host;
        }

        return array_values($normalized);
    }
}
