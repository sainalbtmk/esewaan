<?php

namespace App\Support;

use App\Models\ContractDocument;
use App\Models\RentalContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class StatusTindakan
{
    public const BARU_TERIMA_PERMOHONAN = 'baru_terima_permohonan';

    public const SEMAKAN_CP_SEBELUM_BPH = 'semakan_cp_sebelum_bph';

    public const MINIT_CERAIAN_KELULUSAN_KOS = 'minit_ceraian_kelulusan_kos';

    public const PERTIMBANGAN_MOF = 'pertimbangan_mof';

    public const PERTIMBANGAN_KE = 'pertimbangan_ke';

    public const PERTIMBANGAN_BPH_SUPS = 'pertimbangan_bph_sups';

    public const SEDIA_DRAF_PERJANJIAN = 'sedia_draf_perjanjian';

    public const SEMAKAN_CP_SEBELUM_PUU = 'semakan_cp_sebelum_puu';

    public const SEMAKAN_PUU = 'semakan_puu';

    public const KUIRI_PUU = 'kuiri_puu';

    public const PINDAAN_SYOR_PUU_TANDATANGAN_PEMILIK = 'pindaan_syor_puu_tandatangan_pemilik';

    public const TANDATANGAN_PENYEWA_AADK = 'tandatangan_penyewa_aadk';

    public const TINDAKAN_METERI_SETEM = 'tindakan_meteri_setem';

    /**
     * Ordered status labels shown on the dashboard "Status Tindakan" section.
     *
     * @return list<array{key: string, label: string}>
     */
    public static function definitions(): array
    {
        return [
            [
                'key' => self::BARU_TERIMA_PERMOHONAN,
                'label' => 'Belum Terima Permohonan',
            ],
            [
                'key' => self::SEMAKAN_CP_SEBELUM_BPH,
                'label' => 'Semakan Cawangan Pembangunan',
            ],
            [
                'key' => self::MINIT_CERAIAN_KELULUSAN_KOS,
                'label' => 'Minit Ceraian Kelulusan Kos',
            ],
            [
                'key' => self::PERTIMBANGAN_MOF,
                'label' => 'Pertimbangan MOF',
            ],
            [
                'key' => self::PERTIMBANGAN_KE,
                'label' => 'Pertimbangan KE',
            ],
            [
                'key' => self::PERTIMBANGAN_BPH_SUPS,
                'label' => 'Pertimbangan BPH/SUPS',
            ],
            [
                'key' => self::SEDIA_DRAF_PERJANJIAN,
                'label' => 'Sedia Draf Perjanjian',
            ],
            [
                'key' => self::SEMAKAN_CP_SEBELUM_PUU,
                'label' => 'Semakan Cawangan Pembangunan Sebelum ke PUU',
            ],
            [
                'key' => self::SEMAKAN_PUU,
                'label' => 'Semakan PUU',
            ],
            [
                'key' => self::KUIRI_PUU,
                'label' => 'Kuiri PUU',
            ],
            [
                'key' => self::PINDAAN_SYOR_PUU_TANDATANGAN_PEMILIK,
                'label' => 'Pindaan Berdasarkan Syor PUU dan Tandatangan Pemilik Premis',
            ],
            [
                'key' => self::TANDATANGAN_PENYEWA_AADK,
                'label' => 'Tandatangan Penyewa (AADK)',
            ],
            [
                'key' => self::TINDAKAN_METERI_SETEM,
                'label' => 'Tindakan Mati Setem',
            ],
        ];
    }

    /**
     * Workflows reached after Negeri presses "Hantar ke Ibu Pejabat",
     * before the application becomes an active rental contract.
     *
     * @return list<string>
     */
    public static function sentToHqWorkflows(): array
    {
        return array_merge(
            [RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ],
            RentalContract::draftAgreementWorkflows(),
        );
    }

    /**
     * One current status per contract, the same placement the portfolio pie uses.
     * A contract is not counted again on every step it has not cleared.
     */
    public static function currentKey(RentalContract $contract): ?string
    {
        if ($contract->isSuperseded()) {
            return null;
        }

        if (RentalStatusReport::isUrgentFollowUp($contract)) {
            return self::BARU_TERIMA_PERMOHONAN;
        }

        if ($contract->isHqApproved()) {
            return null;
        }

        $placement = RentalStatusReport::placement($contract);

        if ($placement === null) {
            return null;
        }

        return match ($placement['rumusan']) {
            'Pertimbangan MOF' => self::PERTIMBANGAN_MOF,
            'Pertimbangan KE' => self::PERTIMBANGAN_KE,
            'Pertimbangan BPH' => self::PERTIMBANGAN_BPH_SUPS,
            'Semakan PUU' => self::SEMAKAN_PUU,
            'Kuiri PUU' => self::KUIRI_PUU,
            'Sedia Draf Perjanjian' => self::SEDIA_DRAF_PERJANJIAN,
            'Pinda Perjanjian', 'Tandatangan Pemilik Premis' => self::PINDAAN_SYOR_PUU_TANDATANGAN_PEMILIK,
            'Tandatangan Penyewa (AADK)' => self::TANDATANGAN_PENYEWA_AADK,
            'Mati setem' => self::TINDAKAN_METERI_SETEM,
            HqJrpChecklist::STATUS_MINIT_CERAIAN => self::MINIT_CERAIAN_KELULUSAN_KOS,
            'Menunggu Semakan Ibu Pejabat', 'Semakan Ibu Pejabat', 'Pengesahan Ibu Pejabat' => self::SEMAKAN_CP_SEBELUM_BPH,
            default => null,
        };
    }

    /**
     * @return list<array{key: string, status: string, count: int}>
     */
    public static function breakdownWithCounts(?string $negeri = null, ?string $daerah = null): array
    {
        $counts = [];

        foreach (self::scopedContracts($negeri, $daerah) as $contract) {
            $key = self::currentKey($contract);

            if ($key === null) {
                continue;
            }

            $counts[$key] = ($counts[$key] ?? 0) + 1;
        }

        return collect(self::definitions())
            ->map(fn (array $row): array => [
                'key' => $row['key'],
                'status' => $row['label'],
                'count' => $counts[$row['key']] ?? 0,
            ])
            ->values()
            ->all();
    }

    public static function label(string $key): ?string
    {
        foreach (self::definitions() as $row) {
            if ($row['key'] === $key) {
                return $row['label'];
            }
        }

        return null;
    }

    public static function keyFrom(mixed $value): ?string
    {
        return is_string($value) && self::label($value) !== null ? $value : null;
    }

    public static function listUrl(string $key): string
    {
        $parameters = ['status_tindakan' => $key];

        if ($key === self::BARU_TERIMA_PERMOHONAN) {
            return route('kontrak-sewaan.index', $parameters);
        }

        return route('status-permohonan.index', $parameters);
    }

    /**
     * @param  Builder<RentalContract>  $query
     */
    public static function applyFilter(Builder $query, ?string $key, ?string $negeri = null): void
    {
        if (! is_string($key) || self::label($key) === null) {
            return;
        }

        $query->whereIn($query->getModel()->getQualifiedKeyName(), self::matchingIds($key, $negeri));
    }

    /**
     * @return list<int>
     */
    public static function matchingIds(string $key, ?string $negeri = null): array
    {
        return self::scopedContracts($negeri)
            ->filter(fn (RentalContract $contract): bool => self::currentKey($contract) === $key)
            ->pluck('id')
            ->map(fn (mixed $id): int => (int) $id)
            ->all();
    }

    /**
     * @return Collection<int, RentalContract>
     */
    private static function scopedContracts(?string $negeri, ?string $daerah = null): Collection
    {
        return RentalContract::query()
            ->notSuperseded()
            ->where(function (Builder $query): void {
                $query->whereIn('workflow_tahap', self::sentToHqWorkflows())
                    ->orWhereIn('workflow_tahap', RentalContract::hqApprovedWorkflows());
            })
            ->tap(fn (Builder $query) => self::scopePremise($query, $negeri, $daerah))
            ->with([
                'parentContract',
                'followUpApplications',
                'documents' => function ($query): void {
                    $query->select(['id', 'contract_id', 'jenis', 'semakan_status'])
                        ->where('jenis', ContractDocument::JENIS_DRAF_PERJANJIAN);
                },
            ])
            ->get();
    }

    /**
     * @param  Builder<RentalContract>  $query
     */
    private static function scopePremise(Builder $query, ?string $negeri, ?string $daerah): void
    {
        if (! filled($negeri) && ! filled($daerah)) {
            return;
        }

        $query->whereHas('premise', function (Builder $premiseQuery) use ($negeri, $daerah): void {
            if (filled($negeri)) {
                $premiseQuery->where('negeri', $negeri);
            }

            if (filled($daerah)) {
                $premiseQuery->where('daerah', $daerah);
            }
        });
    }
}
