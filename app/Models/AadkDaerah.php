<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AadkDaerah extends Model
{
    /** @use HasFactory<\Database\Factories\AadkDaerahFactory> */
    use HasFactory;

    protected $table = 'aadk_daerah';

    protected $fillable = [
        'negeri',
        'daerah',
        'nama_pejabat',
        'alamat',
        'ketua_daerah',
        'telefon',
        'faks',
        'emel',
        'susunan',
    ];

    /**
     * @return array<string, list<string>>
     */
    public static function groupedNames(): array
    {
        /** @var Collection<string, Collection<int, self>> $grouped */
        $grouped = self::query()
            ->orderBy('susunan')
            ->get(['negeri', 'daerah'])
            ->groupBy('negeri');

        return $grouped
            ->map(fn (Collection $rows): array => $rows->pluck('daerah')->values()->all())
            ->all();
    }

    /**
     * @return list<string>
     */
    public static function namesForNegeri(string $negeri): array
    {
        return self::query()
            ->where('negeri', $negeri)
            ->orderBy('susunan')
            ->pluck('daerah')
            ->all();
    }
}
