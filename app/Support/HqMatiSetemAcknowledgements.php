<?php

namespace App\Support;

use DateTimeImmutable;
use DateTimeZone;

class HqMatiSetemAcknowledgements
{
    public const SALINAN_DITERIMA = 'salinan_diterima_dari_negeri';

    public const DIMUAT_NAIK_SISTEM_PREMIS = 'perjanjian_dimuat_naik_sistem_premis';

    /**
     * @return list<string>
     */
    public static function keys(): array
    {
        return [
            self::SALINAN_DITERIMA,
            self::DIMUAT_NAIK_SISTEM_PREMIS,
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function labels(): array
    {
        return [
            self::SALINAN_DITERIMA => '1 Salinan Dokumen Perjanjian yang telah dimatikan setem diterima dari AADK Negeri',
            self::DIMUAT_NAIK_SISTEM_PREMIS => 'Perjanjian telah dimuat naik ke dalam Sistem PROMIS',
        ];
    }

    /**
     * @param  array<string, mixed>  $acknowledgements
     */
    public static function dateFor(array $acknowledgements, string $key): ?string
    {
        $dates = is_array($acknowledgements['dates'] ?? null) ? $acknowledgements['dates'] : [];
        $value = $dates[$key] ?? null;

        return is_string($value) && self::normalizeDate($value) !== null ? $value : null;
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public static function normalizeInput(array $input): array
    {
        $dates = is_array($input['dates'] ?? null) ? $input['dates'] : [];
        $acknowledgements = [];
        $normalizedDates = [];

        foreach (self::keys() as $key) {
            $checked = filter_var($input[$key] ?? false, FILTER_VALIDATE_BOOLEAN);
            $acknowledgements[$key] = $checked;

            if (! $checked) {
                continue;
            }

            $normalizedDates[$key] = self::normalizeDate($dates[$key] ?? null) ?? self::today();
        }

        $acknowledgements['dates'] = $normalizedDates;

        return $acknowledgements;
    }

    private static function today(): string
    {
        return now('Asia/Kuala_Lumpur')->toDateString();
    }

    private static function normalizeDate(mixed $value): ?string
    {
        if (! is_string($value) || preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) !== 1) {
            return null;
        }

        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value, new DateTimeZone('Asia/Kuala_Lumpur'));

        if ($date === false || $date->format('Y-m-d') !== $value) {
            return null;
        }

        return $value;
    }
}
