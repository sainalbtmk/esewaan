<?php

namespace App\Support;

use DateTimeImmutable;
use DateTimeZone;

class NegeriMatiSetemAcknowledgements
{
    public const MATI_SETEM_LHDN = 'mati_setem_lhdn_acknowledged';

    public const EDARAN_PEMILIK = 'edaran_pemilik_acknowledged';

    public const SALINAN_DIHANTAR_CP = 'salinan_dihantar_cawangan_pembangunan_acknowledged';

    /**
     * @return list<string>
     */
    public static function keys(): array
    {
        return [
            self::MATI_SETEM_LHDN,
            self::EDARAN_PEMILIK,
            self::SALINAN_DIHANTAR_CP,
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function labels(): array
    {
        return [
            self::MATI_SETEM_LHDN => '3 Salinan Dokumen Perjanjian telah dimatikan setem di LHDN',
            self::EDARAN_PEMILIK => '1 Salinan Dokumen Perjanjian telah diedar kepada Pemilik Premis',
            self::SALINAN_DIHANTAR_CP => 'Salinan dihantar ke Cawangan Pembangunan melalui Pos/Kourier',
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
