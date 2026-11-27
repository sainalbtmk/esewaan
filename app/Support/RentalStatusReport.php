<?php

namespace App\Support;

use App\Models\ContractDocument;
use App\Models\RentalContract;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class RentalStatusReport
{
    public const SECTION_BPH = 'bph';

    public const SECTION_PUU = 'puu';

    public const SECTION_MOF = 'mof';

    public const SECTION_KE = 'ke';

    public const SECTION_MINIT = 'minit';

    public const SECTION_HQ = 'hq';

    public const SECTION_PTJ = 'ptj';

    public const BUCKET_BERKUATKUASA = 'berkuatkuasa';

    public const BUCKET_BELUM_TERIMA = 'belum_terima';

    /**
     * @return array<string, string>
     */
    public static function sectionTitles(): array
    {
        return [
            self::SECTION_BPH => 'Pertimbangan BPH',
            self::SECTION_PUU => 'Semakan PUU',
            self::SECTION_MOF => 'Pertimbangan MOF',
            self::SECTION_KE => 'Pertimbangan KE',
            self::SECTION_MINIT => 'Minit Ceraian Kelulusan Kos',
            self::SECTION_HQ => 'Semakan Ibu Pejabat',
            self::SECTION_PTJ => 'Tindakan PTJ',
        ];
    }

    public static function title(): string
    {
        $asAt = now()->timezone(config('app.timezone'))->format('j.n.Y');

        return 'Laporan Status Sewaan Premis AADK setakat '.$asAt;
    }

    /**
     * @param  Collection<int, RentalContract>  $contracts
     * @return list<array{key: string, title: string, rows: Collection<int, RentalContract>}>
     */
    public static function groups(Collection $contracts): array
    {
        /** @var array<string, list<RentalContract>> $grouped */
        $grouped = [];

        foreach ($contracts as $contract) {
            $placement = self::placement($contract);

            if ($placement === null) {
                continue;
            }

            $contract->setAttribute('laporan_status_rumusan', $placement['rumusan']);
            $contract->setAttribute('laporan_status_tindakan', $placement['tindakan']);
            $contract->setAttribute('laporan_status_diterima', $placement['diterima']);

            $grouped[$placement['section']][] = $contract;
        }

        $groups = [];

        foreach (self::sectionTitles() as $key => $title) {
            $rows = collect($grouped[$key] ?? [])
                ->sortBy(fn (RentalContract $contract): array => self::sectionSort($key, $contract))
                ->values();

            if ($rows->isEmpty()) {
                continue;
            }

            $groups[] = [
                'key' => $key,
                'title' => $title,
                'rows' => $rows,
            ];
        }

        return $groups;
    }

    /**
     * Tindakan PTJ lists the most urgent remaining term first. Other sections stay in negeri and premise order.
     *
     * @return list<int|string>
     */
    private static function sectionSort(string $section, RentalContract $contract): array
    {
        $identity = [
            $contract->premise?->negeri ?? '',
            $contract->premise?->nama_ptj ?? '',
            $contract->id,
        ];

        if ($section !== self::SECTION_PTJ) {
            return $identity;
        }

        return [
            self::bakiBulan($contract) ?? PHP_INT_MAX,
            ...$identity,
        ];
    }

    /**
     * @return array{section: string, rumusan: string, tindakan: string, diterima: bool}|null
     */
    public static function placement(RentalContract $contract): ?array
    {
        if ($contract->isSuperseded()) {
            return null;
        }

        if ($contract->isHqApproved()) {
            if (! $contract->isExpired() && ! $contract->isContractEndWithinEightMonths()) {
                return null;
            }

            if (self::followUpAlreadySent($contract)) {
                return null;
            }

            return self::row(self::SECTION_PTJ, 'Belum Terima Permohonan', 'PTJ', false);
        }

        if (! in_array($contract->workflow_tahap, StatusTindakan::sentToHqWorkflows(), true)) {
            return null;
        }

        if ($contract->workflow_tahap === RentalContract::WORKFLOW_SEMAKAN_PUU) {
            return self::row(self::SECTION_PUU, 'Semakan PUU', 'PUU', true);
        }

        if (self::isKuiriPuu($contract)) {
            return self::row(self::SECTION_PTJ, 'Kuiri PUU', 'PTJ', true);
        }

        return match ($contract->workflow_tahap) {
            RentalContract::WORKFLOW_PINDAAN_BERDASARKAN_PUU => self::row(self::SECTION_PTJ, 'Pinda Perjanjian', 'PTJ', true),
            RentalContract::WORKFLOW_PENYEDIAAN_DRAF_PERJANJIAN => (int) $contract->semakan_count > 0
                ? self::row(self::SECTION_PTJ, 'Pinda Perjanjian', 'PTJ', true)
                : self::row(self::SECTION_PTJ, 'Sedia Draf Perjanjian', 'PTJ', true),
            RentalContract::WORKFLOW_DRAF_PERJANJIAN_LULUS => self::row(self::SECTION_PTJ, 'Tandatangan Pemilik Premis', 'PTJ', true),
            RentalContract::WORKFLOW_DRAF_DIKEMBALIKAN_HQ => self::row(self::SECTION_HQ, 'Tandatangan Penyewa (AADK)', 'CP', true),
            RentalContract::WORKFLOW_MATI_SETEM => self::row(self::SECTION_PTJ, 'Mati setem', 'PTJ', true),
            RentalContract::WORKFLOW_PENGESAHAN_IBU_PEJABAT => self::row(self::SECTION_HQ, 'Pengesahan Ibu Pejabat', 'CP', true),
            RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ => self::hqReviewPlacement($contract),
            default => null,
        };
    }

    public static function kategoriLabel(RentalContract $contract): string
    {
        return match ($contract->kategori_permohonan) {
            ApplicationCategories::BARU => 'BAHARU',
            ApplicationCategories::LANJUTAN => 'LANJUTAN',
            ApplicationCategories::PINDAH => 'PINDAH',
            default => '–',
        };
    }

    public static function bakiBulan(RentalContract $contract): ?int
    {
        $end = $contract->contractEndDate();

        if ($end === null) {
            return null;
        }

        $months = (int) Carbon::today()->diffInMonths($end->copy()->startOfDay(), false);

        if ($end->copy()->startOfDay()->lt(Carbon::today()) && $months >= 0) {
            return -1;
        }

        return $months;
    }

    /**
     * Dashboard pie slice. A contract that is still in force stays
     * "berkuatkuasa", including when its follow-up is already with Ibu Pejabat.
     * The eight-month and overdue contracts that have not sent that follow-up
     * are "Belum Terima Permohonan", not a PTJ workflow action.
     */
    public static function portfolioBucket(RentalContract $contract): ?string
    {
        if ($contract->isSuperseded()) {
            return null;
        }

        if ($contract->isHqApproved()) {
            if (self::isUrgentFollowUp($contract)) {
                return self::BUCKET_BELUM_TERIMA;
            }

            if ($contract->isExpired()) {
                return null;
            }

            return self::BUCKET_BERKUATKUASA;
        }

        return self::placement($contract)['section'] ?? null;
    }

    public static function isUrgentFollowUp(RentalContract $contract): bool
    {
        if (! $contract->isHqApproved() || $contract->isSuperseded() || self::followUpAlreadySent($contract)) {
            return false;
        }

        $months = self::bakiBulan($contract);

        return $months !== null && $months < 8;
    }

    /**
     * @param  Collection<int, RentalContract>  $contracts
     * @return array{
     *     total: int,
     *     slices: list<array{key: string, label: string, count: int, percent: float, color: string}>,
     *     urgentCount: int,
     *     urgentPercent: float,
     *     urgentRows: Collection<int, RentalContract>
     * }
     */
    public static function portfolio(Collection $contracts): array
    {
        /** @var array<string, int> $counts */
        $counts = [];
        $urgent = collect();

        foreach ($contracts as $contract) {
            $bucket = self::portfolioBucket($contract);

            if ($bucket === null) {
                continue;
            }

            $counts[$bucket] = ($counts[$bucket] ?? 0) + 1;

            if (self::isUrgentFollowUp($contract)) {
                $urgent->push($contract);
            }
        }

        $total = (int) array_sum($counts);
        $sliceCounts = [];

        foreach (self::portfolioSliceDefinitions() as $key => [$label, $color]) {
            $count = $counts[$key] ?? 0;

            if ($count === 0) {
                continue;
            }

            $sliceCounts[$key] = $count;
        }

        $percents = self::slicePercents($sliceCounts, $total);
        $slices = [];

        foreach (self::portfolioSliceDefinitions() as $key => [$label, $color]) {
            if (! array_key_exists($key, $sliceCounts)) {
                continue;
            }

            $slices[] = [
                'key' => $key,
                'label' => $label,
                'count' => $sliceCounts[$key],
                'percent' => $percents[$key],
                'color' => $color,
            ];
        }

        $urgentRows = $urgent
            ->sortBy(fn (RentalContract $contract): array => [
                self::bakiBulan($contract) ?? 0,
                $contract->premise?->negeri ?? '',
                $contract->premise?->nama_ptj ?? '',
                $contract->id,
            ])
            ->values();

        return [
            'total' => $total,
            'slices' => $slices,
            'urgentCount' => $urgentRows->count(),
            'urgentPercent' => $total > 0 ? round(($urgentRows->count() / $total) * 100, 1) : 0.0,
            'urgentRows' => $urgentRows,
        ];
    }

    /**
     * One-decimal percentages that add up to 100.
     *
     * @param  array<string, int>  $counts
     * @return array<string, float>
     */
    private static function slicePercents(array $counts, int $total): array
    {
        if ($total === 0 || $counts === []) {
            return [];
        }

        $bases = [];
        $remainders = [];

        foreach ($counts as $key => $count) {
            $exactTenths = ($count / $total) * 1000;
            $bases[$key] = (int) floor($exactTenths);
            $remainders[$key] = $exactTenths - $bases[$key];
        }

        $positions = array_flip(array_keys($counts));
        $order = array_keys($counts);
        usort($order, function (string $left, string $right) use ($remainders, $positions): int {
            $byRemainder = $remainders[$right] <=> $remainders[$left];

            if ($byRemainder !== 0) {
                return $byRemainder;
            }

            return $positions[$left] <=> $positions[$right];
        });

        $shortfall = 1000 - array_sum($bases);

        if ($shortfall > 0) {
            for ($index = 0; $index < $shortfall; $index++) {
                $bases[$order[$index % count($order)]]++;
            }
        }

        $percents = [];

        foreach (array_keys($counts) as $key) {
            $percents[$key] = $bases[$key] / 10;
        }

        return $percents;
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function portfolioSliceDefinitions(): array
    {
        return [
            self::BUCKET_BERKUATKUASA => ['Kontrak Sedang Berkuatkuasa', '#22C55E'],
            self::BUCKET_BELUM_TERIMA => ['Belum Terima Permohonan', '#FB7185'],
            self::SECTION_BPH => ['Pertimbangan BPH', '#F5B301'],
            self::SECTION_MOF => ['Pertimbangan MOF', '#F472B6'],
            self::SECTION_KE => ['Pertimbangan KE', '#2DD4BF'],
            self::SECTION_MINIT => ['Minit Ceraian Kelulusan Kos', '#6366F1'],
            self::SECTION_PUU => ['Semakan PUU', '#A78BFA'],
            self::SECTION_PTJ => ['Tindakan PTJ', '#FB923C'],
            self::SECTION_HQ => ['Semakan Ibu Pejabat', '#60A5FA'],
        ];
    }

    public static function isBakiBulanKritikal(RentalContract $contract): bool
    {
        $months = self::bakiBulan($contract);

        return $months !== null && $months < 0;
    }

    public static function isBakiBulanRed(RentalContract $contract): bool
    {
        $months = self::bakiBulan($contract);

        return $months !== null && $months <= 3;
    }

    public static function isBakiBulanYellow(RentalContract $contract): bool
    {
        $months = self::bakiBulan($contract);

        return $months !== null && $months >= 4 && $months <= 8;
    }

    /**
     * Same stage as Senarai Permohonan. A required MOF or KE step is not
     * current until that checklist item is actually ticked.
     *
     * @return array{section: string, rumusan: string, tindakan: string, diterima: bool}
     */
    private static function hqReviewPlacement(RentalContract $contract): array
    {
        return match (HqJrpChecklist::pendingHqReviewStatusLabel($contract)) {
            HqJrpChecklist::STATUS_MINIT_CERAIAN => self::row(
                self::SECTION_MINIT,
                HqJrpChecklist::STATUS_MINIT_CERAIAN,
                'CP',
                true,
            ),
            HqJrpChecklist::STATUS_PERMOHONAN_MOF => self::row(self::SECTION_MOF, 'Pertimbangan MOF', 'MOF', true),
            HqJrpChecklist::STATUS_PERMOHONAN_KE => self::row(self::SECTION_KE, 'Pertimbangan KE', 'KE', true),
            HqJrpChecklist::STATUS_PERMOHONAN_BPH => self::row(self::SECTION_BPH, 'Pertimbangan BPH', 'BPH', true),
            default => self::row(self::SECTION_HQ, 'Menunggu Semakan Ibu Pejabat', 'CP', true),
        };
    }

    public static function receivedStatusLabel(RentalContract $contract): string
    {
        if (! (bool) ($contract->laporan_status_diterima ?? false)) {
            return 'Belum Terima';
        }

        return $contract->applicationStatusLabel();
    }

    /**
     * @return array{section: string, rumusan: string, tindakan: string, diterima: bool}
     */
    private static function row(string $section, string $rumusan, string $tindakan, bool $diterima): array
    {
        return [
            'section' => $section,
            'rumusan' => $rumusan,
            'tindakan' => $tindakan,
            'diterima' => $diterima,
        ];
    }

    private static function followUpAlreadySent(RentalContract $contract): bool
    {
        $sent = fn (RentalContract $followUp): bool => $followUp->workflow_tahap !== RentalContract::WORKFLOW_MENUNGGU_PROCEED_NEGERI;

        if ($contract->relationLoaded('followUpApplications')) {
            return $contract->followUpApplications->contains($sent);
        }

        return $contract->followUpApplications()->where('workflow_tahap', '!=', RentalContract::WORKFLOW_MENUNGGU_PROCEED_NEGERI)->exists();
    }

    private static function isKuiriPuu(RentalContract $contract): bool
    {
        if (in_array($contract->workflow_tahap, [
            RentalContract::WORKFLOW_DRAF_PERJANJIAN_LULUS,
            RentalContract::WORKFLOW_DRAF_DIKEMBALIKAN_HQ,
            RentalContract::WORKFLOW_MATI_SETEM,
            RentalContract::WORKFLOW_PENGESAHAN_IBU_PEJABAT,
        ], true)) {
            return false;
        }

        $documents = $contract->relationLoaded('documents')
            ? $contract->documents
            : $contract->documents()->where('jenis', ContractDocument::JENIS_DRAF_PERJANJIAN)->get();

        $rejected = $documents->contains(
            fn (ContractDocument $document): bool => $document->semakan_status === ContractDocument::SEMAKAN_DIBATALKAN
        );
        $approved = $documents->contains(
            fn (ContractDocument $document): bool => $document->semakan_status === ContractDocument::SEMAKAN_DILULUSKAN
        );

        return $rejected && ! $approved;
    }
}
