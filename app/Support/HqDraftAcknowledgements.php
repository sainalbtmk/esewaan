<?php

namespace App\Support;

use DateTimeImmutable;
use DateTimeZone;

class HqDraftAcknowledgements
{
    public const TERIMA_PERJANJIAN_PEMILIK = 'terima_perjanjian_pemilik_acknowledged';

    public const DIHANTAR_PKP_SAKSI = 'dihantar_pkp_saksi_acknowledged';

    public const DIHANTAR_TKPP_PENYEWA = 'dihantar_tkpp_penyewa_acknowledged';

    public const DIKEMBALIKAN_NEGERI = 'dikembalikan_negeri_mati_setem_acknowledged';

    /**
     * Earlier acknowledgement kept so contracts already ticked before this
     * wording change still leave the Tandatangan Penyewa count.
     */
    public const PERJANJIAN_DITANDATANGANI = 'perjanjian_ditandatangani_acknowledged';

    /**
     * @return list<string>
     */
    public static function keys(): array
    {
        return [
            self::TERIMA_PERJANJIAN_PEMILIK,
            self::DIHANTAR_PKP_SAKSI,
            self::DIHANTAR_TKPP_PENYEWA,
            self::DIKEMBALIKAN_NEGERI,
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function labels(): array
    {
        return [
            self::TERIMA_PERJANJIAN_PEMILIK => 'Cawangan Pembangunan terima Perjanjian yang telah ditandatangani oleh Pemilik Premis',
            self::DIHANTAR_PKP_SAKSI => 'Perjanjian dihantar ke Pejabat PKP untuk tandatangan Saksi',
            self::DIHANTAR_TKPP_PENYEWA => 'Perjanjian dihantar ke Pejabat TKPP untuk tandatangan Penyewa',
            self::DIKEMBALIKAN_NEGERI => 'Perjanjian dikembalikan ke AADK Negeri untuk tindakan mati setem dan edaran kepada Pemilik Premis',
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
