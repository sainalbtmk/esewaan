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

    public const SECTION_HQ = 'hq';

    public const SECTION_PTJ = 'ptj';

    public const BUCKET_BERKUATKUASA = 'berkuatkuasa';

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
     * Portfolio slice for the dashboard pie. Active contracts that still have
     * more than 8 months, and no follow-up already with Ibu Pejabat, stay in
     * force. Overdue contracts that have not submitted a follow-up sit with PTJ.
     */
    public static function portfolioBucket(RentalContract $contract): ?string
    {
        if ($contract->isSuperseded()) {
            return null;
        }

        $placement = self::placement($contract);

        if ($placement !== null) {
            return $placement['section'];
        }

        if (! $contract->isHqApproved() || self::followUpAlreadySent($contract)) {
            return null;
        }

        if ($contract->isExpired()) {
            return self::SECTION_PTJ;
        }

        return self::BUCKET_BERKUATKUASA;
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
        $slices = [];

        foreach (self::portfolioSliceDefinitions() as $key => [$label, $color]) {
            $count = $counts[$key] ?? 0;

            if ($count === 0) {
                continue;
            }

            $slices[] = [
                'key' => $key,
                'label' => $label,
                'count' => $count,
                'percent' => $total > 0 ? round(($count / $total) * 100, 1) : 0.0,
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
     * @return array<string, array{0: string, 1: string}>
     */
    public static function portfolioSliceDefinitions(): array
    {
        return [
            self::SECTION_BPH => ['Pertimbangan BPH', '#4a6fa5'],
            self::SECTION_PUU => ['Semakan PUU', '#7eb6d9'],
            self::SECTION_MOF => ['Pertimbangan MOF', '#2f5d8c'],
            self::SECTION_KE => ['Pertimbangan KE', '#8fafc8'],
            self::SECTION_PTJ => ['Tindakan PTJ', '#5b7c99'],
            self::SECTION_HQ => ['Semakan Ibu Pejabat', '#3d5a80'],
            self::BUCKET_BERKUATKUASA => ['Kontrak Sedang Berkuatkuasa', '#1e3a5f'],
        ];
    }

    public static function isBakiBulanKritikal(RentalContract $contract): bool
    {
        $months = self::bakiBulan($contract);

        return $months !== null && $months < 0;
    }

    /**
     * @return array{section: string, rumusan: string, tindakan: string, diterima: bool}
     */
    private static function hqReviewPlacement(RentalContract $contract): array
    {
        if (HqJrpChecklist::requiresMofCheckbox($contract) && ! self::checklistChecked($contract, HqJrpChecklist::AADK_RECEIVE_MOF_COMMENTS)) {
            return self::row(self::SECTION_MOF, 'Pertimbangan MOF', 'MOF', true);
        }

        if (HqJrpChecklist::requiresEpuCheckbox($contract) && ! self::checklistChecked($contract, HqJrpChecklist::AADK_RECEIVE_EPU_COMMENTS)) {
            return self::row(self::SECTION_KE, 'Pertimbangan KE', 'KE', true);
        }

        if (! self::checklistChecked($contract, HqJrpChecklist::AADK_RECEIVE_BPH_APPROVAL)) {
            return self::row(self::SECTION_BPH, 'Pertimbangan BPH', 'BPH', true);
        }

        return self::row(self::SECTION_HQ, 'Menunggu Semakan Ibu Pejabat', 'CP', true);
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

    private static function checklistChecked(RentalContract $contract, string $key): bool
    {
        $checklist = $contract->hq_jrp_checklist;

        if (! is_array($checklist)) {
            return false;
        }

        return filter_var($checklist[$key] ?? false, FILTER_VALIDATE_BOOLEAN) === true;
    }
}
