<?php

namespace App\Http\Controllers;

use App\Models\ContractDocument;
use App\Models\RentalContract;
use App\Support\RentalStatusReport;
use App\Support\StatusTindakan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user?->isAdmin()) {
            return redirect()->route('login');
        }

        if ($user->isAdminNegeri() && ! filled($user->negeri)) {
            return redirect()->route('status-permohonan.index');
        }

        $negeri = $user->isAdminNegeri() ? $user->negeri : null;

        $permohonanBaharu = RentalContract::query()
            ->where('workflow_tahap', RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ)
            ->tap(fn (Builder $query) => $this->scopeToNegeri($query, $negeri))
            ->count();

        $dalamTindakanByStatus = collect(StatusTindakan::breakdownWithCounts($negeri));
        $progressPermohonan = (int) $dalamTindakanByStatus->sum('count');

        $activeContracts = RentalContract::query()
            ->hqApproved()
            ->notSuperseded()
            ->kontrakNotExpired()
            ->with('premise')
            ->tap(fn (Builder $query) => $this->scopeToNegeri($query, $negeri))
            ->get();

        $kontrakAktif = $activeContracts->count();

        $kontrakTigaBulanList = $activeContracts
            ->filter->isContractEndWithinThreeMonths()
            ->sortBy(fn (RentalContract $contract) => $contract->daysUntilContractEnd())
            ->values();

        $kontrakLapanBulanList = $activeContracts
            ->filter(fn (RentalContract $contract): bool => $contract->isContractEndWithinEightMonths()
                && ! $contract->isContractEndWithinThreeMonths())
            ->sortBy(fn (RentalContract $contract) => $contract->daysUntilContractEnd())
            ->values();

        $kontrakTigaBulan = $kontrakTigaBulanList->count();
        $kontrakLapanBulan = $kontrakLapanBulanList->count();
        $kontrakLebihLapanBulan = max(0, $kontrakAktif - $kontrakTigaBulan - $kontrakLapanBulan);

        return view('dashboard.index', [
            'viewer' => $user,
            'scopedNegeri' => $negeri,
            'permohonanBaharu' => $permohonanBaharu,
            'progressPermohonan' => $progressPermohonan,
            'dalamTindakanByStatus' => $dalamTindakanByStatus,
            'kontrakAktif' => $kontrakAktif,
            'kontrakTigaBulan' => $kontrakTigaBulan,
            'kontrakLapanBulan' => $kontrakLapanBulan,
            'kontrakLebihLapanBulan' => $kontrakLebihLapanBulan,
            'alertListTigaBulan' => $kontrakTigaBulanList->take(20),
            'alertListLapanBulan' => $kontrakLapanBulanList->take(20),
            'portfolio' => RentalStatusReport::portfolio($this->portfolioContracts($negeri)),
            'portfolioYear' => now()->year,
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection<int, RentalContract>
     */
    private function portfolioContracts(?string $negeri): \Illuminate\Support\Collection
    {
        return RentalContract::query()
            ->notSuperseded()
            ->where(function (Builder $query): void {
                $query->whereIn('workflow_tahap', StatusTindakan::sentToHqWorkflows())
                    ->orWhereIn('workflow_tahap', RentalContract::hqApprovedWorkflows());
            })
            ->tap(fn (Builder $query) => $this->scopeToNegeri($query, $negeri))
            ->with([
                'premise',
                'parentContract',
                'followUpApplications',
                'documents' => fn ($query) => $query
                    ->select(['id', 'contract_id', 'jenis', 'semakan_status'])
                    ->where('jenis', ContractDocument::JENIS_DRAF_PERJANJIAN),
            ])
            ->get();
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
}
