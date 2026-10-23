<?php

namespace App\Support;

use DateTimeImmutable;
use DateTimeZone;

class NegeriDraftAcknowledgements
{
    public const DRAF_AKHIR_DITERIMA = 'draf_akhir_diterima_acknowledged';

    public const DOKUMEN_DISEDIAKAN = 'dokumen_perjanjian_disediakan_acknowledged';

    public const DOKUMEN_DITANDATANGANI = 'dokumen_perjanjian_ditandatangani_acknowledged';

    public const DOKUMEN_ASAL_DIHANTAR = 'dokumen_asal_dihantar_acknowledged';

    /**
     * @return list<string>
     */
    public static function keys(): array
    {
        return [
            self::DRAF_AKHIR_DITERIMA,
            self::DOKUMEN_DISEDIAKAN,
            self::DOKUMEN_DITANDATANGANI,
            self::DOKUMEN_ASAL_DIHANTAR,
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function labels(): array
    {
        return [
            self::DRAF_AKHIR_DITERIMA => 'Draf akhir diterima untuk penyediaan dokumen perjanjian',
            self::DOKUMEN_DISEDIAKAN => '3 Salinan Dokumen Perjanjian telah disediakan',
            self::DOKUMEN_DITANDATANGANI => 'Dokumen Perjanjian telah ditandatangani oleh pemilik premis',
            self::DOKUMEN_ASAL_DIHANTAR => 'Dokumen perjanjian telah dihantar melalui kurier / Serahan tangan kepada Cawangan Pembangunan',
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
