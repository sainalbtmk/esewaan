<?php

namespace App\Support;

use App\Models\RentalContract;

class RentDifferenceNotice
{
    public const BELOW_LABEL = 'Perbezaan < RM500';

    public const ABOVE_LABEL = 'Perbezaan ≥ RM500';

    /**
     * @return array{text: string, band: 'below'|'above'}
     */
    public static function describe(float $parentRent, float $newRent): array
    {
        $difference = $newRent - $parentRent;
        $magnitude = abs($difference);
        $below = $magnitude < RentalContract::RENT_DIFFERENCE_THRESHOLD;
        $sign = $difference > 0 ? '+' : ($difference < 0 ? '−' : '');

        $text = sprintf(
            '%s — Lama RM%s → Baharu RM%s (%sRM%s%s)',
            $below ? self::BELOW_LABEL : self::ABOVE_LABEL,
            self::money($parentRent),
            self::money($newRent),
            $sign,
            self::money($magnitude),
            self::percentSuffix($parentRent, $difference, $sign),
        );

        return [
            'text' => $text,
            'band' => $below ? 'below' : 'above',
        ];
    }

    private static function percentSuffix(float $parentRent, float $difference, string $sign): string
    {
        if (abs($parentRent) < 0.00001) {
            return '';
        }

        return ', '.$sign.self::money(abs($difference / $parentRent * 100)).'%';
    }

    private static function money(float $amount): string
    {
        return number_format($amount, 2);
    }
}
