<?php

namespace App\Support;

class UploadLimits
{
    public const APP_MAX_KILOBYTES = 10240;

    public const DRAFT_MAX_KILOBYTES = 40960;

    public static function phpUploadMaxKilobytes(): int
    {
        return (int) floor(self::iniSizeToBytes(ini_get('upload_max_filesize')) / 1024);
    }

    public static function effectiveMaxKilobytes(): int
    {
        return self::capToPhpLimit(self::APP_MAX_KILOBYTES);
    }

    public static function effectiveDraftMaxKilobytes(): int
    {
        return self::capToPhpLimit(self::DRAFT_MAX_KILOBYTES);
    }

    public static function humanAppLimit(): string
    {
        return self::formatKilobytes(self::APP_MAX_KILOBYTES);
    }

    public static function humanEffectiveLimit(): string
    {
        return self::formatKilobytes(self::effectiveMaxKilobytes());
    }

    public static function humanDraftLimit(): string
    {
        return self::formatKilobytes(self::effectiveDraftMaxKilobytes());
    }

    public static function humanPhpUploadLimit(): string
    {
        return self::formatBytes(self::iniSizeToBytes(ini_get('upload_max_filesize')));
    }

    public static function uploadFailureMessage(int $uploadError, ?int $applicationMaxKilobytes = null): string
    {
        $applicationMaxKilobytes ??= self::APP_MAX_KILOBYTES;
        $effectiveLimit = self::formatKilobytes(self::capToPhpLimit($applicationMaxKilobytes));
        $phpLimit = self::humanPhpUploadLimit();

        return match ($uploadError) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => self::phpUploadMaxKilobytes() < $applicationMaxKilobytes
                ? "Saiz fail melebihi had pelayan ({$phpLimit}). Had aplikasi ialah {$effectiveLimit}. Sila hubungi pentadbir atau kecilkan fail PDF."
                : "Saiz fail melebihi had maksimum ({$effectiveLimit}). Sila gunakan fail PDF yang lebih kecil.",
            UPLOAD_ERR_PARTIAL => 'Fail hanya dimuat naik sebahagian. Sila cuba lagi.',
            UPLOAD_ERR_NO_FILE => 'Sila pilih fail PDF untuk dimuat naik.',
            default => "Fail gagal dimuat naik. Sila pastikan fail adalah PDF dan tidak melebihi {$effectiveLimit}.",
        };
    }

    private static function capToPhpLimit(int $applicationMaxKilobytes): int
    {
        $phpLimit = self::phpUploadMaxKilobytes();

        if ($phpLimit <= 0) {
            return $applicationMaxKilobytes;
        }

        return min($applicationMaxKilobytes, $phpLimit);
    }

    private static function iniSizeToBytes(string|false $value): int
    {
        if ($value === false || $value === '') {
            return 0;
        }

        $value = trim($value);
        $unit = strtolower(substr($value, -1));
        $number = (float) $value;

        return (int) match ($unit) {
            'g' => $number * 1024 * 1024 * 1024,
            'm' => $number * 1024 * 1024,
            'k' => $number * 1024,
            default => $number,
        };
    }

    private static function formatKilobytes(int $kilobytes): string
    {
        if ($kilobytes >= 1024) {
            $megabytes = $kilobytes / 1024;

            return rtrim(rtrim(number_format($megabytes, 1, '.', ''), '0'), '.').'MB';
        }

        return $kilobytes.'KB';
    }

    private static function formatBytes(int $bytes): string
    {
        return self::formatKilobytes((int) floor($bytes / 1024));
    }
}
