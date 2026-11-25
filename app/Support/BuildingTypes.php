<?php

namespace App\Support;

class BuildingTypes
{
    public const KOMPLEKS_KERAJAAN = 'kompleks kerajaan';

    public const KOMERSIAL = 'komersial';

    public const RUMAH_KEDIAMAN = 'rumah kediaman';

    public const RUMAH_KEDAI = 'rumah kedai';

    /**
     * @return array<string, string>
     */
    public static function all(): array
    {
        return [
            self::KOMPLEKS_KERAJAAN => 'Kompleks kerajaan',
            self::KOMERSIAL => 'Komersial',
            self::RUMAH_KEDIAMAN => 'Rumah Kediaman',
            self::RUMAH_KEDAI => 'Rumah Kedai',
        ];
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_keys(self::all());
    }
}
