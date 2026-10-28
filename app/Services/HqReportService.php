<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\ContractDocument;
use App\Models\Premise;
use App\Models\RentalContract;
use App\Support\ContractNegeriReport;
use App\Support\MalaysianStates;
use App\Support\RentalStatusReport;
use App\Support\StatusTindakan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class HqReportService
{
    public const TYPE_RINGKASAN = 'ringkasan';

    public const TYPE_PERMOHONAN = 'permohonan';

    public const TYPE_KONTRAK_AKTIF = 'kontrak_aktif';

    public const TYPE_KONTRAK_NEGERI = 'kontrak_negeri';

    public const TYPE_STATUS_SEWAAN = 'status_sewaan';

    /**
     * @return array<string, string>
     */
    public static function typeLabels(): array
    {
        return [
            self::TYPE_RINGKASAN => 'Ringkasan Keseluruhan',
            self::TYPE_PERMOHONAN => 'Senarai Permohonan',
            self::TYPE_KONTRAK_AKTIF => 'Senarai Kontrak Aktif',
            self::TYPE_KONTRAK_NEGERI => 'Laporan Kontrak mengikut Negeri',
            self::TYPE_STATUS_SEWAAN => 'Laporan Status Sewaan',
        ];
    }

    /**
     * @return list<string>
     */
    public static function typeValues(): array
    {
        return array_keys(self::typeLabels());
    }

    /**
     * @return array{
     *     jenis: string,
     *     jenisLabel: string,
     *     negeri: string|null,
     *     daerah: string|null,
     *     scopeLabel: string,
     *     breakdownMode: string,
     *     generatedAt: string,
     *     summary: array{permohonanBaharu: int, dalamTindakan: int, kontrakAktif: int, kontrakLapanBulan: int, kontrakTigaBulan: int},
     *     statusBreakdown: list<array{key: string, status: string, count: int}>,
     *     negeriBreakdown: list<array{label: string, permohonan: int, kontrak_aktif: int}>,
     *     rows: Collection<int, RentalContract>,
     *     statusGroups: list<array{key: string, title: string, rows: Collection<int, RentalContract>}>
     * }
     */
    public function build(?string $jenis = null, ?string $negeri = null, ?string $daerah = null): array
    {
        $jenis = in_array($jenis, self::typeValues(), true) ? $jenis : self::TYPE_RINGKASAN;
        $negeri = filled($negeri) && in_array($negeri, MalaysianStates::values(), true) ? $negeri : null;
        $daerah = filled($negeri) && filled($daerah) ? $daerah : null;

        $activeContracts = $this->activeContractsQuery($negeri, $daerah)
            ->with('premise')
            ->get();

        $overdueContracts = $this->overdueContractsQuery($negeri, $daerah)
            ->with('premise')
            ->get();

        $kontrakTigaBulan = $activeContracts
            ->filter->isContractEndWithinThreeMonths()
            ->count() + $overdueContracts->count();

        $kontrakLapanBulan = $activeContracts
            ->filter(fn (RentalContract $contract): bool => $contract->isContractEndWithinEightMonths()
                && ! $contract->isContractEndWithinThreeMonths())
            ->count();

        $statusBreakdown = collect(StatusTindakan::breakdownWithCounts($negeri, $daerah))
            ->filter(fn (array $row): bool => $row['count'] > 0)
            ->values()
            ->all();

        $rows = $this->rowsForType($jenis, $negeri, $daerah, $activeContracts, $overdueContracts);
        $statusGroups = [];

        if ($jenis === self::TYPE_STATUS_SEWAAN) {
            $statusGroups = RentalStatusReport::groups($rows);
            $rows = collect($statusGroups)
                ->flatMap(fn (array $group) => $group['rows'])
                ->values();
        }

        return [
            'jenis' => $jenis,
            'jenisLabel' => self::typeLabels()[$jenis],
            'negeri' => $negeri,
            'daerah' => $daerah,
            'scopeLabel' => $this->scopeLabel($negeri, $daerah),
            'breakdownMode' => filled($negeri) ? 'daerah' : 'negeri',
            'generatedAt' => now()->timezone(config('app.timezone'))->format('d/m/Y H:i'),
            'summary' => [
                'permohonanBaharu' => $this->permohonanBaharuQuery($negeri, $daerah)->count(),
                'dalamTindakan' => (int) collect($statusBreakdown)->sum('count'),
                'kontrakAktif' => $activeContracts->count(),
                'kontrakLapanBulan' => $kontrakLapanBulan,
                'kontrakTigaBulan' => $kontrakTigaBulan,
            ],
            'statusBreakdown' => $statusBreakdown,
            'negeriBreakdown' => $this->locationBreakdown($negeri, $daerah),
            'rows' => $rows,
            'statusGroups' => $statusGroups,
        ];
    }

    /**
     * @param  Builder<RentalContract>  $query
     * @return Builder<RentalContract>
     */
    private function scopeToLocation(Builder $query, ?string $negeri, ?string $daerah): Builder
    {
        if (! filled($negeri) && ! filled($daerah)) {
            return $query;
        }

        return $query->whereHas('premise', function (Builder $premiseQuery) use ($negeri, $daerah): void {
            if (filled($negeri)) {
                $premiseQuery->where('negeri', $negeri);
            }

            if (filled($daerah)) {
                $premiseQuery->where('daerah', $daerah);
            }
        });
    }

    /**
     * @return Builder<RentalContract>
     */
    private function permohonanBaharuQuery(?string $negeri, ?string $daerah): Builder
    {
        return RentalContract::query()
            ->where('workflow_tahap', RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ)
            ->tap(fn (Builder $query) => $this->scopeToLocation($query, $negeri, $daerah));
    }

    /**
     * @return Builder<RentalContract>
     */
    private function activeContractsQuery(?string $negeri, ?string $daerah): Builder
    {
        return RentalContract::query()
            ->hqApproved()
            ->notSuperseded()
            ->kontrakNotExpired()
            ->tap(fn (Builder $query) => $this->scopeToLocation($query, $negeri, $daerah));
    }

    /**
     * HQ-approved contracts whose remaining term is already below zero months.
     *
     * @return Builder<RentalContract>
     */
    private function overdueContractsQuery(?string $negeri, ?string $daerah): Builder
    {
        return RentalContract::query()
            ->hqApproved()
            ->notSuperseded()
            ->kontrakExpired()
            ->tap(fn (Builder $query) => $this->scopeToLocation($query, $negeri, $daerah));
    }

    /**
     * @return Builder<RentalContract>
     */
    private function permohonanQuery(?string $negeri, ?string $daerah): Builder
    {
        return RentalContract::query()
            ->whereNotIn('workflow_tahap', RentalContract::hqApprovedWorkflows())
            ->tap(fn (Builder $query) => $this->scopeToLocation($query, $negeri, $daerah))
            ->with('premise')
            ->latest('updated_at');
    }

    /**
     * @param  Collection<int, RentalContract>  $activeContracts
     * @param  Collection<int, RentalContract>  $overdueContracts
     * @return Collection<int, RentalContract>
     */
    private function rowsForType(string $jenis, ?string $negeri, ?string $daerah, Collection $activeContracts, Collection $overdueContracts): Collection
    {
        return match ($jenis) {
            self::TYPE_KONTRAK_AKTIF => $activeContracts
                ->sortBy(fn (RentalContract $contract) => $contract->premise?->nama_ptj ?? '')
                ->values(),
            self::TYPE_PERMOHONAN => $this->permohonanQuery($negeri, $daerah)->get(),
            self::TYPE_STATUS_SEWAAN => $this->statusSewaanContracts($negeri, $daerah),
            self::TYPE_KONTRAK_NEGERI => $this->attachChronology(
                RentalContract::query()
                    ->notSuperseded()
                    ->tap(fn (Builder $query) => $this->scopeToLocation($query, $negeri, $daerah))
                    ->with([
                        'premise',
                        'processLogs' => fn ($query) => $query->orderBy('tarikh_tindakan')->orderBy('id'),
                    ])
                    ->get()
                    ->sortBy(fn (RentalContract $contract): array => [
                        $contract->premise?->negeri ?? '',
                        $contract->premise?->daerah ?? '',
                        $contract->premise?->nama_ptj ?? '',
                        $contract->id,
                    ])
                    ->values()
            ),
            default => $this->permohonanQuery($negeri, $daerah)->get()
                ->concat($activeContracts)
                ->concat($overdueContracts)
                ->sortBy(fn (RentalContract $contract) => [
                    $contract->premise?->negeri ?? '',
                    $contract->premise?->daerah ?? '',
                    $contract->premise?->nama_ptj ?? '',
                ])
                ->values(),
        };
    }

    /**
     * @return Collection<int, RentalContract>
     */
    private function statusSewaanContracts(?string $negeri, ?string $daerah): Collection
    {
        $inProcess = RentalContract::query()
            ->notSuperseded()
            ->whereIn('workflow_tahap', StatusTindakan::sentToHqWorkflows())
            ->tap(fn (Builder $query) => $this->scopeToLocation($query, $negeri, $daerah))
            ->with([
                'premise',
                'parentContract',
                'documents' => fn ($query) => $query
                    ->select(['id', 'contract_id', 'jenis', 'semakan_status'])
                    ->where('jenis', ContractDocument::JENIS_DRAF_PERJANJIAN),
            ])
            ->get();

        $awaitingFollowUp = RentalContract::query()
            ->hqApproved()
            ->notSuperseded()
            ->whereDoesntHave(
                'followUpApplications',
                fn (Builder $query) => $query->where(
                    'workflow_tahap',
                    '!=',
                    RentalContract::WORKFLOW_MENUNGGU_PROCEED_NEGERI,
                ),
            )
            ->tap(fn (Builder $query) => $this->scopeToLocation($query, $negeri, $daerah))
            ->with(['premise', 'parentContract', 'followUpApplications'])
            ->get()
            ->filter(fn (RentalContract $contract): bool => $contract->isExpired()
                || $contract->isContractEndWithinEightMonths());

        return $inProcess
            ->concat($awaitingFollowUp)
            ->unique('id')
            ->values();
    }

    /**
     * @param  Collection<int, RentalContract>  $contracts
     * @return Collection<int, RentalContract>
     */
    private function attachChronology(Collection $contracts): Collection
    {
        if ($contracts->isEmpty()) {
            return $contracts;
        }

        $ids = $contracts->pluck('id')->map(fn (mixed $id): int => (int) $id)->all();

        $logsByContract = ActivityLog::query()
            ->whereIn('metadata->contract_id', $ids)
            ->orderBy('created_at')
            ->orderBy('id')
            ->get()
            ->groupBy(fn (ActivityLog $log): int => (int) ($log->metadata['contract_id'] ?? 0));

        return $contracts->each(function (RentalContract $contract) use ($logsByContract): void {
            $contract->setAttribute(
                'laporan_kronologi',
                ContractNegeriReport::chronologyLines(
                    $contract,
                    $logsByContract->get($contract->id, collect()),
                ),
            );
        });
    }

    private function scopeLabel(?string $negeri, ?string $daerah): string
    {
        if (filled($negeri) && filled($daerah)) {
            return $negeri.' · Daerah '.$daerah;
        }

        if (filled($negeri)) {
            return $negeri;
        }

        return 'Semua negeri';
    }

    /**
     * @return list<array{label: string, permohonan: int, kontrak_aktif: int}>
     */
    private function locationBreakdown(?string $negeri, ?string $daerah): array
    {
        if (filled($negeri)) {
            $labels = filled($daerah)
                ? [$daerah]
                : Premise::query()
                    ->where('negeri', $negeri)
                    ->where('daerah', '!=', '')
                    ->whereHas('rentalContracts')
                    ->distinct()
                    ->orderBy('daerah')
                    ->pluck('daerah')
                    ->all();

            return collect($labels)
                ->map(function (string $label) use ($negeri): array {
                    return [
                        'label' => $label,
                        'permohonan' => $this->permohonanQuery($negeri, $label)->count(),
                        'kontrak_aktif' => $this->activeContractsQuery($negeri, $label)->count(),
                    ];
                })
                ->filter(fn (array $row): bool => $row['permohonan'] > 0 || $row['kontrak_aktif'] > 0)
                ->values()
                ->all();
        }

        return collect(MalaysianStates::values())
            ->map(function (string $state): array {
                return [
                    'label' => $state,
                    'permohonan' => $this->permohonanQuery($state, null)->count(),
                    'kontrak_aktif' => $this->activeContractsQuery($state, null)->count(),
                ];
            })
            ->filter(fn (array $row): bool => $row['permohonan'] > 0 || $row['kontrak_aktif'] > 0)
            ->values()
            ->all();
    }
}
