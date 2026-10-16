<?php

namespace App\Services;

use App\Models\RentalContract;
use App\Support\MalaysianStates;
use App\Support\StatusTindakan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class HqReportService
{
    public const TYPE_RINGKASAN = 'ringkasan';

    public const TYPE_PERMOHONAN = 'permohonan';

    public const TYPE_KONTRAK_AKTIF = 'kontrak_aktif';

    /**
     * @return array<string, string>
     */
    public static function typeLabels(): array
    {
        return [
            self::TYPE_RINGKASAN => 'Ringkasan Keseluruhan',
            self::TYPE_PERMOHONAN => 'Senarai Permohonan',
            self::TYPE_KONTRAK_AKTIF => 'Senarai Kontrak Aktif',
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
     *     rows: Collection<int, RentalContract>
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
            'rows' => $this->rowsForType($jenis, $negeri, $activeContracts),
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
