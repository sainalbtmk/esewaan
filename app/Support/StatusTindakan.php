<?php

namespace App\Support;

use App\Models\ContractDocument;
use App\Models\RentalContract;
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
                'label' => 'Semakan Cawangan Pembangunan Sebelum ke BPH',
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
     * @return list<array{key: string, status: string, count: int}>
     */
    public static function breakdownWithCounts(?string $negeri = null): array
    {
        $sentContracts = self::sentToHqContracts($negeri);
        $underEightMonthsCount = self::underEightMonthsActiveCount($negeri);

        return collect(self::definitions())
            ->map(function (array $row) use ($sentContracts, $underEightMonthsCount): array {
                $count = match ($row['key']) {
                    self::BARU_TERIMA_PERMOHONAN => $underEightMonthsCount,
                    self::KUIRI_PUU => $sentContracts->filter(fn (RentalContract $contract): bool => self::isKuiriPuu($contract))->count(),
                    default => $sentContracts->reject(fn (RentalContract $contract): bool => self::isCleared($contract, $row['key']))->count(),
                };

                return [
                    'key' => $row['key'],
                    'status' => $row['label'],
                    'count' => $count,
                ];
            })
            ->values()
            ->all();
    }

    /**
     * Active HQ-approved contracts with remaining term under 8 months
     * whose follow-up has not yet been sent to HQ for verification.
     */
    public static function underEightMonthsActiveCount(?string $negeri = null): int
    {
        return RentalContract::query()
            ->hqApproved()
            ->notSuperseded()
            ->kontrakNotExpired()
            ->whereDoesntHave(
                'followUpApplications',
                fn ($query) => $query->where(
                    'workflow_tahap',
                    '!=',
                    RentalContract::WORKFLOW_MENUNGGU_PROCEED_NEGERI,
                ),
            )
            ->when(
                filled($negeri),
                fn ($query) => $query->whereHas('premise', fn ($premiseQuery) => $premiseQuery->where('negeri', $negeri))
            )
            ->get()
            ->filter->isContractEndWithinEightMonths()
            ->count();
    }

    /**
     * @return Collection<int, RentalContract>
     */
    private static function sentToHqContracts(?string $negeri): Collection
    {
        return RentalContract::query()
            ->notSuperseded()
            ->whereIn('workflow_tahap', self::sentToHqWorkflows())
            ->when(
                filled($negeri),
                fn ($query) => $query->whereHas('premise', fn ($premiseQuery) => $premiseQuery->where('negeri', $negeri))
            )
            ->with(['documents' => function ($query): void {
                $query->select(['id', 'contract_id', 'jenis', 'semakan_status'])
                    ->where('jenis', ContractDocument::JENIS_DRAF_PERJANJIAN);
            }])
            ->get();
    }

    private static function isCleared(RentalContract $contract, string $statusKey): bool
    {
        return match ($statusKey) {
            self::SEMAKAN_CP_SEBELUM_BPH => self::checklistChecked($contract, HqJrpChecklist::AADK_RECEIVE_BPH_APPROVAL),
            self::MINIT_CERAIAN_KELULUSAN_KOS => self::checklistChecked($contract, HqJrpChecklist::MOF),
            self::PERTIMBANGAN_MOF => self::checklistChecked($contract, HqJrpChecklist::AADK_RECEIVE_MOF_COMMENTS),
            self::PERTIMBANGAN_KE => self::checklistChecked($contract, HqJrpChecklist::AADK_RECEIVE_EPU_COMMENTS),
            self::PERTIMBANGAN_BPH_SUPS => self::checklistChecked($contract, HqJrpChecklist::AADK_SUBMIT_BPH),
            self::SEDIA_DRAF_PERJANJIAN, self::SEMAKAN_CP_SEBELUM_PUU => self::hasReachedSemakanOne($contract),
            self::SEMAKAN_PUU => self::puuReviewApproved($contract),
            self::PINDAAN_SYOR_PUU_TANDATANGAN_PEMILIK => self::acknowledgementChecked(
                $contract->negeri_draft_acknowledgements,
                NegeriDraftAcknowledgements::DOKUMEN_DITANDATANGANI,
            ),
            self::TANDATANGAN_PENYEWA_AADK => self::acknowledgementChecked(
                $contract->hq_draft_acknowledgements,
                HqDraftAcknowledgements::PERJANJIAN_DITANDATANGANI,
            ),
            self::TINDAKAN_METERI_SETEM => self::acknowledgementChecked(
                $contract->negeri_mati_setem_acknowledgements,
                NegeriMatiSetemAcknowledgements::MATI_SETEM_LHDN,
            ),
            default => false,
        };
    }

    /**
     * Sedia Draf and Semakan Cawangan Pembangunan Sebelum ke PUU drop once
     * Status Semasa has reached Semakan PUU 1, including later rounds.
     */
    private static function hasReachedSemakanOne(RentalContract $contract): bool
    {
        if ((int) $contract->semakan_count >= 1) {
            return true;
        }

        return in_array($contract->workflow_tahap, [
            RentalContract::WORKFLOW_SEMAKAN_PUU,
            RentalContract::WORKFLOW_PINDAAN_BERDASARKAN_PUU,
            RentalContract::WORKFLOW_DRAF_PERJANJIAN_LULUS,
            RentalContract::WORKFLOW_DRAF_DIKEMBALIKAN_HQ,
            RentalContract::WORKFLOW_MATI_SETEM,
        ], true);
    }

    /**
     * Semakan PUU and Kuiri PUU drop after "Ya, Luluskan".
     */
    private static function puuReviewApproved(RentalContract $contract): bool
    {
        if (in_array($contract->workflow_tahap, [
            RentalContract::WORKFLOW_DRAF_PERJANJIAN_LULUS,
            RentalContract::WORKFLOW_DRAF_DIKEMBALIKAN_HQ,
            RentalContract::WORKFLOW_MATI_SETEM,
        ], true)) {
            return true;
        }

        return $contract->documents->contains(
            fn (ContractDocument $document): bool => $document->semakan_status === ContractDocument::SEMAKAN_DILULUSKAN
        );
    }

    /**
     * Kuiri PUU rises after "Ya, Semak Semula" and stays until PUU is approved.
     */
    private static function isKuiriPuu(RentalContract $contract): bool
    {
        $rejected = $contract->documents->contains(
            fn (ContractDocument $document): bool => $document->semakan_status === ContractDocument::SEMAKAN_DIBATALKAN
        );

        return $rejected && ! self::puuReviewApproved($contract);
    }

    private static function checklistChecked(RentalContract $contract, string $key): bool
    {
        $checklist = $contract->hq_jrp_checklist;

        if (! is_array($checklist)) {
            return false;
        }

        return filter_var($checklist[$key] ?? false, FILTER_VALIDATE_BOOLEAN) === true;
    }

    /**
     * @param  array<string, mixed>|null  $acknowledgements
     */
    private static function acknowledgementChecked(?array $acknowledgements, string $key): bool
    {
        if (! is_array($acknowledgements)) {
            return false;
        }

        return filter_var($acknowledgements[$key] ?? false, FILTER_VALIDATE_BOOLEAN) === true;
    }
}
