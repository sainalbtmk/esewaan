<?php

namespace App\Support;

use App\Models\RentalContract;
use Illuminate\Database\Eloquent\Builder;

class RentalContractListSearch
{
    /**
     * @param  array{include_admin_negeri?: bool, include_deleted_by?: bool, include_delete_reason?: bool, include_peringkat_proses?: bool}  $options
     */
    public static function apply(Builder $query, string $search, array $options = []): void
    {
        $search = trim($search);

        if ($search === '') {
            return;
        }

        $like = ListSearch::like($search);
        $needle = mb_strtolower($search);

        $query->where(function (Builder $query) use ($like, $needle, $options): void {
            $query->whereHas('premise', function (Builder $premiseQuery) use ($like): void {
                $premiseQuery->where('nama_ptj', 'like', $like)
                    ->orWhere('negeri', 'like', $like)
                    ->orWhere('nama_pemilik', 'like', $like)
                    ->orWhere('alamat_penuh', 'like', $like);
            })
                ->orWhereHas('submittedBy', function (Builder $userQuery) use ($like): void {
                    $userQuery->where('name', 'like', $like)
                        ->orWhere('email', 'like', $like);
                });

            if (($options['include_admin_negeri'] ?? false) === true) {
                $query->orWhereHas('adminNegeriUser', fn (Builder $userQuery) => $userQuery->where('name', 'like', $like));
            }

            if (($options['include_deleted_by'] ?? false) === true) {
                $query->orWhereHas('deletedBy', fn (Builder $userQuery) => $userQuery->where('name', 'like', $like));
            }

            $query->orWhere('kategori_permohonan', 'like', $like)
                ->orWhere('workflow_tahap', 'like', $like)
                ->orWhere('status_aktif', 'like', $like);

            if (($options['include_peringkat_proses'] ?? false) === true) {
                $query->orWhere('peringkat_proses', 'like', $like);
            }

            if (($options['include_delete_reason'] ?? false) === true) {
                $query->orWhere('delete_reason', 'like', $like);
            }

            $categoryMatches = ListSearch::matchingKeys(ApplicationCategories::labels(), $needle);

            if ($categoryMatches !== []) {
                $query->orWhereIn('kategori_permohonan', $categoryMatches);
            }

            $statusMatches = ListSearch::matchingKeys([
                'aktif' => 'Aktif',
                'dalam_proses' => 'Dalam proses',
                'tamat_tempoh' => 'Tamat tempoh',
                'batal' => 'Batal',
            ], $needle);

            if ($statusMatches !== []) {
                $query->orWhereIn('status_aktif', $statusMatches);
            }

            $workflowMatches = ListSearch::matchingKeys(self::workflowLabelMap(), $needle);

            if ($workflowMatches !== []) {
                $query->orWhereIn('workflow_tahap', $workflowMatches);
            }

            self::applyPendingHqReviewStatusSearch($query, $needle);
            self::applyKuiriPuuStatusSearch($query, $needle);

            if (str_contains($needle, 'sedia') && str_contains($needle, 'hq')) {
                $query->orWhere('workflow_tahap', RentalContract::WORKFLOW_MENUNGGU_PROCEED_NEGERI);
            }

            if (str_contains($needle, 'tarik semula')) {
                $query->orWhere('withdrawal_status', RentalContract::WITHDRAWAL_PENDING);
            }
        });
    }

    private static function applyKuiriPuuStatusSearch(Builder $query, string $needle): void
    {
        if (! str_contains('kuiri puu', $needle)) {
            return;
        }

        $query->orWhere(function (Builder $query): void {
            $query->where('workflow_tahap', RentalContract::WORKFLOW_PENYEDIAAN_DRAF_PERJANJIAN)
                ->where('semakan_count', '>', 0);
        });
    }

    private static function applyPendingHqReviewStatusSearch(Builder $query, string $needle): void
    {
        $labels = HqJrpChecklist::pendingHqReviewStatusSteps();
        $labels['fallback'] = HqJrpChecklist::PENDING_HQ_REVIEW_STATUS;

        foreach ($labels as $key => $label) {
            if (! str_contains(mb_strtolower($label), $needle)) {
                continue;
            }

            $query->orWhere(function (Builder $query) use ($key): void {
                if ($key === 'fallback') {
                    self::whereHqReviewStatusCleared($query);

                    return;
                }

                self::wherePendingHqReviewStep($query, $key);
            });
        }
    }

    private static function wherePendingHqReviewStep(Builder $query, string $key): void
    {
        $steps = array_keys(HqJrpChecklist::pendingHqReviewStatusSteps());
        $index = array_search($key, $steps, true);

        $query->where('workflow_tahap', RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ);

        foreach (array_slice($steps, 0, $index === false ? 0 : $index) as $previous) {
            $query->where("hq_jrp_checklist->{$previous}", true);
        }

        $query->where(function (Builder $query) use ($key): void {
            $query->whereNull('hq_jrp_checklist')
                ->orWhereNull("hq_jrp_checklist->{$key}")
                ->orWhere("hq_jrp_checklist->{$key}", false);
        });
    }

    private static function whereHqReviewStatusCleared(Builder $query): void
    {
        $query->where('workflow_tahap', RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ);

        foreach (array_keys(HqJrpChecklist::pendingHqReviewStatusSteps()) as $key) {
            $query->where("hq_jrp_checklist->{$key}", true);
        }
    }

    /**
     * @return array<string, string>
     */
    private static function workflowLabelMap(): array
    {
        return [
            RentalContract::WORKFLOW_SEMAKAN_PUU => 'Semakan PUU',
            RentalContract::WORKFLOW_DRAF_PERJANJIAN_LULUS => RentalContract::DRAF_LULUS_STATUS_LABEL,
            RentalContract::WORKFLOW_DRAF_DIKEMBALIKAN_HQ => RentalContract::DRAF_DIKEMBALIKAN_HQ_STATUS_LABEL,
            RentalContract::WORKFLOW_MENUNGGU_PROCEED_NEGERI => 'Menunggu langkah tindakan',
            RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI => 'Menunggu semakan pentadbir negeri',
            RentalContract::WORKFLOW_MATI_SETEM => 'Menunggu Mati Setem',
        ];
    }
}
