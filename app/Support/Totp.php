<?php

namespace App\Support;

class Totp
{
    private const ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    public static function generateSecret(int $length = 32): string
    {
        $secret = '';

        for ($i = 0; $i < $length; $i++) {
            $secret .= self::ALPHABET[random_int(0, 31)];
        }

        return $secret;
    }

    public static function verify(string $secret, string $code, int $window = 1): bool
    {
        $code = preg_replace('/\s+/', '', $code) ?? '';

        if (! preg_match('/^\d{6}$/', $code)) {
            return false;
        }

        $slice = intdiv(time(), 30);

        for ($offset = -$window; $offset <= $window; $offset++) {
            if (hash_equals(self::codeAt($secret, $slice + $offset), $code)) {
                return true;
            }
        }

        return false;
    }

    public static function code(string $secret, ?int $timestamp = null): string
    {
        return self::codeAt($secret, intdiv($timestamp ?? time(), 30));
    }

    public static function provisioningUri(string $secret, string $account, string $issuer): string
    {
        $label = rawurlencode($issuer.':'.$account);

        return 'otpauth://totp/'.$label
            .'?secret='.$secret
            .'&issuer='.rawurlencode($issuer)
            .'&digits=6&period=30';
    }

    private static function codeAt(string $secret, int $counter): string
    {
        $binarySecret = self::base32Decode($secret);
        $high = ($counter >> 32) & 0xFFFFFFFF;
        $low = $counter & 0xFFFFFFFF;
        $hash = hash_hmac('sha1', pack('N2', $high, $low), $binarySecret, true);
        $offset = ord(substr($hash, -1)) & 0x0F;
        $truncated = unpack('N', substr($hash, $offset, 4))[1] & 0x7FFFFFFF;

        return str_pad((string) ($truncated % 1000000), 6, '0', STR_PAD_LEFT);
    }

    private static function base32Decode(string $secret): string
    {
        $secret = strtoupper((string) preg_replace('/[^A-Z2-7]/', '', $secret));
        $buffer = 0;
        $bits = 0;
        $output = '';
        $length = strlen($secret);

        for ($index = 0; $index < $length; $index++) {
            $value = strpos(self::ALPHABET, $secret[$index]);

            if ($value === false) {
                continue;
            }

            $buffer = ($buffer << 5) | $value;
            $bits += 5;

            if ($bits >= 8) {
                $bits -= 8;
                $output .= chr(($buffer >> $bits) & 0xFF);
            }
        }

        return $output;
    }
}
