<?php

namespace App\Support;

use App\Models\RentalContract;

class HqJrpChecklist
{
    public const STATUS_SEMAKAN_IBU_PEJABAT = 'Semakan Ibu Pejabat';

    public const STATUS_MINIT_CERAIAN = 'Minit Ceraian Kelulusan Kos';

    public const STATUS_PERMOHONAN_MOF = 'Permohonan ke MOF';

    public const STATUS_PERMOHONAN_KE = 'Permohonan ke KE';

    public const STATUS_PERMOHONAN_BPH = 'Permohonan ke BPH';

    public const PENDING_HQ_REVIEW_STATUS = self::STATUS_SEMAKAN_IBU_PEJABAT;

    public const MOF = 'mof_negeri_budget_office';

    public const EPU = 'epu_jpm_planning';

    public const KP = 'kp';

    public const AADK_RECEIVE_EPU_COMMENTS = 'aadk_development_receive_epu_comments';

    public const AADK_RECEIVE_MOF_COMMENTS = 'aadk_development_receive_mof_comments';

    public const AADK_SUBMIT_BPH = 'aadk_development_submit_bph';

    public const AADK_RECEIVE_BPH_APPROVAL = 'aadk_development_receive_bph_approval';

    public const KELUASAN_THRESHOLD = 465;

    public const LANJUTAN_RENT_PERCENT_MINIMUM = 10;

    public const LANJUTAN_RENT_DIFFERENCE_MINIMUM = 499;

    /**
     * @return list<string>
     */
    public static function allKeys(): array
    {
        return [
            self::KP,
            self::MOF,
            self::AADK_RECEIVE_MOF_COMMENTS,
            self::EPU,
            self::AADK_RECEIVE_EPU_COMMENTS,
            self::AADK_SUBMIT_BPH,
            self::AADK_RECEIVE_BPH_APPROVAL,
        ];
    }

    /**
     * Checklist items that record a date when checked.
     *
     * @return list<string>
     */
    public static function keysRequiringDate(): array
    {
        return [
            self::KP,
            self::MOF,
            self::AADK_RECEIVE_MOF_COMMENTS,
            self::EPU,
            self::AADK_RECEIVE_EPU_COMMENTS,
            self::AADK_SUBMIT_BPH,
            self::AADK_RECEIVE_BPH_APPROVAL,
        ];
    }

    public static function requiresDate(string $key): bool
    {
        return in_array($key, self::keysRequiringDate(), true);
    }

    /**
     * Checklist items that show a catatan field when checked.
     *
     * @return list<string>
     */
    public static function keysRequiringNote(): array
    {
        return [
            self::MOF,
            self::EPU,
        ];
    }

    public static function requiresNote(string $key): bool
    {
        return in_array($key, self::keysRequiringNote(), true);
    }

    /**
     * @return list<string>
     */
    public static function pendingHqReviewStatusLabels(): array
    {
        return [
            self::STATUS_SEMAKAN_IBU_PEJABAT,
            self::STATUS_MINIT_CERAIAN,
            self::STATUS_PERMOHONAN_MOF,
            self::STATUS_PERMOHONAN_KE,
            self::STATUS_PERMOHONAN_BPH,
        ];
    }

    /**
     * Status while the application is with Ibu Pejabat. Negeri and HQ share this label.
     *
     * Baharu or Pindah with an increase above 10 percent and at least RM500, and Lanjutan
     * with an increase of at least 10 percent and RM499, shows Minit Ceraian until
     * Kelulusan Pengurusan Tertinggi is ticked, then Permohonan ke MOF until the MOF reply
     * is ticked. Baharu or Pindah above 465 mps shows Permohonan ke KE until the KE reply
     * is ticked. Pindah below 465 mps leaves both KE checklist items disabled. Permohonan
     * ke BPH follows once those paths are done, or for Lanjutan without that rent increase
     * after Ibu Pejabat ticks another action. Lanjutan in that case does not pass through
     * MOF or KE. Anything still untouched, and outside those paths, stays on Semakan Ibu Pejabat.
     */
    public static function pendingHqReviewStatusLabel(RentalContract $contract): string
    {
        $rentPath = self::requiresRentIncreasePath($contract);
        $kePath = self::requiresKePath($contract);

        if ($rentPath && ! self::checklistChecked($contract, self::KP)) {
            return self::STATUS_MINIT_CERAIAN;
        }

        if ($rentPath && ! self::checklistChecked($contract, self::AADK_RECEIVE_MOF_COMMENTS)) {
            return self::STATUS_PERMOHONAN_MOF;
        }

        if ($kePath && ! self::checklistChecked($contract, self::AADK_RECEIVE_EPU_COMMENTS)) {
            return self::STATUS_PERMOHONAN_KE;
        }

        if (! self::anyChecklistChecked($contract)) {
            return self::STATUS_SEMAKAN_IBU_PEJABAT;
        }

        if (! self::checklistChecked($contract, self::AADK_RECEIVE_BPH_APPROVAL)) {
            return self::STATUS_PERMOHONAN_BPH;
        }

        return self::STATUS_SEMAKAN_IBU_PEJABAT;
    }

    public static function requiresRentIncreasePath(RentalContract $contract): bool
    {
        if (! in_array($contract->kategori_permohonan, [
            ApplicationCategories::BARU,
            ApplicationCategories::PINDAH,
            ApplicationCategories::LANJUTAN,
        ], true)) {
            return false;
        }

        $parentRent = $contract->parentRent();
        $difference = $contract->rentDifferenceFromParent();

        if ($contract->kategori_permohonan === ApplicationCategories::LANJUTAN) {
            return self::lanjutanRequiresRentIncreaseAcknowledgement($contract);
        }

        if ($parentRent === null || $parentRent <= 0 || $difference === null || $difference < RentalContract::RENT_DIFFERENCE_THRESHOLD) {
            return false;
        }

        return ($difference / $parentRent) * 100 > 10;
    }

    /**
     * Lanjutan leaves Kelulusan Pengurusan Tertinggi, the MOF application, and the MOF
     * approval receipt unticked when the new rent is under 10 percent or under RM499
     * above the original rent.
     */
    public static function lanjutanRentIncreaseItemsDisabled(RentalContract $contract): bool
    {
        return $contract->kategori_permohonan === ApplicationCategories::LANJUTAN
            && ! self::lanjutanRequiresRentIncreaseAcknowledgement($contract);
    }

    /**
     * @return list<string>
     */
    public static function rentIncreaseAcknowledgementKeys(): array
    {
        return [
            self::KP,
            self::MOF,
            self::AADK_RECEIVE_MOF_COMMENTS,
        ];
    }

    /**
     * Pindah leaves the KE application and the KE comments receipt unticked when the
     * floor area is below 465 mps.
     */
    public static function pindahKeItemsDisabled(RentalContract $contract): bool
    {
        $keluasan = $contract->keluasan_mp;

        return $contract->kategori_permohonan === ApplicationCategories::PINDAH
            && $keluasan !== null
            && (float) $keluasan < self::KELUASAN_THRESHOLD;
    }

    /**
     * @return list<string>
     */
    public static function keAcknowledgementKeys(): array
    {
        return [
            self::EPU,
            self::AADK_RECEIVE_EPU_COMMENTS,
        ];
    }

    public static function checklistItemDisabled(RentalContract $contract, string $key): bool
    {
        if (self::lanjutanRentIncreaseItemsDisabled($contract)
            && in_array($key, self::rentIncreaseAcknowledgementKeys(), true)) {
            return true;
        }

        return self::pindahKeItemsDisabled($contract)
            && in_array($key, self::keAcknowledgementKeys(), true);
    }

    public static function lanjutanRequiresRentIncreaseAcknowledgement(RentalContract $contract): bool
    {
        $parentRent = $contract->parentRent();
        $difference = $contract->rentDifferenceFromParent();

        if ($parentRent === null || $parentRent <= 0 || $difference === null) {
            return false;
        }

        return $difference >= self::LANJUTAN_RENT_DIFFERENCE_MINIMUM
            && ($difference / $parentRent) * 100 >= self::LANJUTAN_RENT_PERCENT_MINIMUM;
    }

    public static function requiresKePath(RentalContract $contract): bool
    {
        if (! in_array($contract->kategori_permohonan, [
            ApplicationCategories::BARU,
            ApplicationCategories::PINDAH,
        ], true)) {
            return false;
        }

        return self::requiresEpuCheckbox($contract);
    }

    private static function checklistChecked(RentalContract $contract, string $key): bool
    {
        $checklist = is_array($contract->hq_jrp_checklist) ? $contract->hq_jrp_checklist : [];

        return filter_var($checklist[$key] ?? false, FILTER_VALIDATE_BOOLEAN) === true;
    }

    private static function anyChecklistChecked(RentalContract $contract): bool
    {
        $ignored = array_merge(
            self::lanjutanRentIncreaseItemsDisabled($contract) ? self::rentIncreaseAcknowledgementKeys() : [],
            self::pindahKeItemsDisabled($contract) ? self::keAcknowledgementKeys() : [],
        );

        foreach (self::allKeys() as $key) {
            if (in_array($key, $ignored, true)) {
                continue;
            }

            if (self::checklistChecked($contract, $key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<string, string>
     */
    public static function definitions(): array
    {
        return [
            self::KP => 'Kelulusan Pengurusan Tertinggi',
            self::MOF => 'Permohonan kepada Kementerian Kewangan (MOF) untuk permohonan Baru / Pindah / Pelanjutan dengan Kenaikan Sewa Lebih 10% @ minimum RM500',
            self::AADK_RECEIVE_MOF_COMMENTS => 'Cawangan Pembangunan AADK menerima Kelulusan daripada MOF melalui KDN',
            self::EPU => 'Permohonan kepada Kementerian Ekonomi (KE) (jika keluasan melebihi 465 mps) — untuk permohonan perpindahan ruang pejabat / ruang pejabat baharu',
            self::AADK_RECEIVE_EPU_COMMENTS => 'Cawangan Pembangunan AADK menerima ulasan daripada Kementerian Ekonomi (KE)',
            self::AADK_SUBMIT_BPH => 'Cawangan Pembangunan AADK mengemukakan permohonan Bahagian Hartanah (BPH) Jabatan Perdana Menteri untuk mendapatkan kelulusan',
            self::AADK_RECEIVE_BPH_APPROVAL => 'Cawangan Pembangunan AADK terima surat kelulusan daripada BPH',
        ];
    }

    /**
     * @return list<string>
     */
    public static function applicableKeys(RentalContract $contract): array
    {
        return self::allKeys();
    }

    public static function requiresMofCheckbox(RentalContract $contract): bool
    {
        $difference = $contract->rentDifferenceFromParent();

        return $difference !== null && $difference >= RentalContract::RENT_DIFFERENCE_THRESHOLD;
    }

    public static function requiresEpuCheckbox(RentalContract $contract): bool
    {
        $keluasan = $contract->keluasan_mp;

        return $keluasan !== null && (float) $keluasan > self::KELUASAN_THRESHOLD;
    }

    /**
     * @param  array<string, mixed>  $input
     * @param  array<string, bool>  $checklist
     * @return array<string, string|null>
     */
    public static function normalizeDatesInput(array $input, array $checklist): array
    {
        $dates = [];

        foreach (self::keysRequiringDate() as $key) {
            $raw = $input[$key] ?? null;
            $dates[$key] = ($checklist[$key] ?? false) && filled($raw)
                ? (string) $raw
                : null;
        }

        return $dates;
    }

    /**
     * @param  array<string, mixed>  $input
     * @param  array<string, bool>  $checklist
     * @return array<string, string|null>
     */
    public static function normalizeNotesInput(array $input, array $checklist): array
    {
        $notes = [];

        foreach (self::keysRequiringNote() as $key) {
            $raw = $input[$key] ?? null;
            $value = is_string($raw) ? trim($raw) : '';
            $notes[$key] = ($checklist[$key] ?? false) && filled($value)
                ? $value
                : null;
        }

        return $notes;
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, bool|array<string, string|null>>
     */
    public static function normalizeInput(array $input, RentalContract $contract): array
    {
        $normalized = [];

        foreach (self::applicableKeys($contract) as $key) {
            $checked = filter_var($input[$key] ?? false, FILTER_VALIDATE_BOOLEAN);
            $normalized[$key] = self::checklistItemDisabled($contract, $key) ? false : $checked;
        }

        $datesInput = is_array($input['dates'] ?? null) ? $input['dates'] : [];
        $normalized['dates'] = self::normalizeDatesInput($datesInput, $normalized);

        $notesInput = is_array($input['notes'] ?? null) ? $input['notes'] : [];
        $normalized['notes'] = self::normalizeNotesInput($notesInput, $normalized);

        return $normalized;
    }
}
