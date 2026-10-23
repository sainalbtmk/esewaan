<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\ContractDocument;
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
     *     generatedAt: string,
     *     summary: array{permohonanBaharu: int, dalamTindakan: int, kontrakAktif: int, kontrakLapanBulan: int, kontrakTigaBulan: int},
     *     statusBreakdown: list<array{key: string, status: string, count: int}>,
     *     negeriBreakdown: list<array{negeri: string, permohonan: int, kontrak_aktif: int}>,
     *     rows: Collection<int, RentalContract>,
     *     statusGroups: list<array{key: string, title: string, rows: Collection<int, RentalContract>}>
     * }
     */
    public function build(?string $jenis = null, ?string $negeri = null): array
    {
        $jenis = in_array($jenis, self::typeValues(), true) ? $jenis : self::TYPE_RINGKASAN;
        $negeri = filled($negeri) && in_array($negeri, MalaysianStates::values(), true) ? $negeri : null;

        $activeContracts = $this->activeContractsQuery($negeri)
            ->with('premise')
            ->get();

        $kontrakTigaBulan = $activeContracts
            ->filter->isContractEndWithinThreeMonths()
            ->count();

        $kontrakLapanBulan = $activeContracts
            ->filter(fn (RentalContract $contract): bool => $contract->isContractEndWithinEightMonths()
                && ! $contract->isContractEndWithinThreeMonths())
            ->count();

        $statusBreakdown = collect(StatusTindakan::breakdownWithCounts($negeri))
            ->filter(fn (array $row): bool => $row['count'] > 0)
            ->values()
            ->all();

        $rows = $this->rowsForType($jenis, $negeri, $activeContracts);
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
            'generatedAt' => now()->timezone(config('app.timezone'))->format('d/m/Y H:i'),
            'summary' => [
                'permohonanBaharu' => $this->permohonanBaharuQuery($negeri)->count(),
                'dalamTindakan' => (int) collect($statusBreakdown)->sum('count'),
                'kontrakAktif' => $activeContracts->count(),
                'kontrakLapanBulan' => $kontrakLapanBulan,
                'kontrakTigaBulan' => $kontrakTigaBulan,
            ],
            'statusBreakdown' => $statusBreakdown,
            'negeriBreakdown' => $this->negeriBreakdown($negeri),
            'rows' => $rows,
            'statusGroups' => $statusGroups,
        ];
    }

    /**
     * @param  Builder<RentalContract>  $query
     * @return Builder<RentalContract>
     */
    private function scopeToNegeri(Builder $query, ?string $negeri): Builder
    {
        if (! filled($negeri)) {
            return $query;
        }

        return $query->whereHas('premise', fn (Builder $premiseQuery) => $premiseQuery->where('negeri', $negeri));
    }

    /**
     * @return Builder<RentalContract>
     */
    private function permohonanBaharuQuery(?string $negeri): Builder
    {
        return RentalContract::query()
            ->where('workflow_tahap', RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ)
            ->tap(fn (Builder $query) => $this->scopeToNegeri($query, $negeri));
    }

    /**
     * @return Builder<RentalContract>
     */
    private function activeContractsQuery(?string $negeri): Builder
    {
        return RentalContract::query()
            ->hqApproved()
            ->notSuperseded()
            ->kontrakNotExpired()
            ->tap(fn (Builder $query) => $this->scopeToNegeri($query, $negeri));
    }

    /**
     * @return Builder<RentalContract>
     */
    private function permohonanQuery(?string $negeri): Builder
    {
        return RentalContract::query()
            ->whereNotIn('workflow_tahap', RentalContract::hqApprovedWorkflows())
            ->tap(fn (Builder $query) => $this->scopeToNegeri($query, $negeri))
            ->with('premise')
            ->latest('updated_at');
    }

    /**
     * @param  Collection<int, RentalContract>  $activeContracts
     * @return Collection<int, RentalContract>
     */
    private function rowsForType(string $jenis, ?string $negeri, Collection $activeContracts): Collection
    {
        return match ($jenis) {
            self::TYPE_KONTRAK_AKTIF => $activeContracts
                ->sortBy(fn (RentalContract $contract) => $contract->premise?->nama_ptj ?? '')
                ->values(),
            self::TYPE_PERMOHONAN => $this->permohonanQuery($negeri)->get(),
            self::TYPE_STATUS_SEWAAN => $this->statusSewaanContracts($negeri),
            self::TYPE_KONTRAK_NEGERI => $this->attachChronology(
                RentalContract::query()
                    ->notSuperseded()
                    ->tap(fn (Builder $query) => $this->scopeToNegeri($query, $negeri))
                    ->with([
                        'premise',
                        'processLogs' => fn ($query) => $query->orderBy('tarikh_tindakan')->orderBy('id'),
                    ])
                    ->get()
                    ->sortBy(fn (RentalContract $contract): array => [
                        $contract->premise?->negeri ?? '',
                        $contract->premise?->nama_ptj ?? '',
                        $contract->id,
                    ])
                    ->values()
            ),
            default => $this->permohonanQuery($negeri)->get()
                ->concat($activeContracts)
                ->sortBy(fn (RentalContract $contract) => [
                    $contract->premise?->negeri ?? '',
                    $contract->premise?->nama_ptj ?? '',
                ])
                ->values(),
        };
    }

    /**
     * @return Collection<int, RentalContract>
     */
    private function statusSewaanContracts(?string $negeri): Collection
    {
        $inProcess = RentalContract::query()
            ->notSuperseded()
            ->whereIn('workflow_tahap', StatusTindakan::sentToHqWorkflows())
            ->tap(fn (Builder $query) => $this->scopeToNegeri($query, $negeri))
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
            ->kontrakNotExpired()
            ->whereDoesntHave(
                'followUpApplications',
                fn (Builder $query) => $query->where(
                    'workflow_tahap',
                    '!=',
                    RentalContract::WORKFLOW_MENUNGGU_PROCEED_NEGERI,
                ),
            )
            ->tap(fn (Builder $query) => $this->scopeToNegeri($query, $negeri))
            ->with(['premise', 'parentContract', 'followUpApplications'])
            ->get()
            ->filter(fn (RentalContract $contract): bool => $contract->isContractEndWithinEightMonths());

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

    /**
     * @return list<array{negeri: string, permohonan: int, kontrak_aktif: int}>
     */
    private function negeriBreakdown(?string $negeri): array
    {
        $states = filled($negeri) ? [$negeri] : MalaysianStates::values();

        return collect($states)
            ->map(function (string $state): array {
                return [
                    'negeri' => $state,
                    'permohonan' => $this->permohonanQuery($state)->count(),
                    'kontrak_aktif' => $this->activeContractsQuery($state)->count(),
                ];
            })
            ->filter(fn (array $row): bool => $row['permohonan'] > 0 || $row['kontrak_aktif'] > 0)
            ->values()
            ->all();
    }
}
