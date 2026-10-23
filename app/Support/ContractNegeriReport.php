<?php

namespace App\Support;

use App\Models\ActivityLog;
use App\Models\ProcessLog;
use App\Models\RentalContract;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ContractNegeriReport
{
    public static function statusPermohonan(RentalContract $contract): string
    {
        return in_array($contract->workflow_tahap, RentalContract::workflowsSentToHeadquarters(), true)
            ? 'Terima'
            : 'Belum Terima';
    }

    public static function isStatusDiterima(RentalContract $contract): bool
    {
        return self::statusPermohonan($contract) === 'Terima';
    }

    public static function kategoriLabel(RentalContract $contract): string
    {
        $label = ApplicationCategories::label($contract->kategori_permohonan);

        return $label === '–' ? $label : mb_strtoupper($label, 'UTF-8');
    }

    public static function jenisLangganan(RentalContract $contract): string
    {
        $value = $contract->premise?->jenis_bangunan;

        if (! filled($value)) {
            return '–';
        }

        return BuildingTypes::all()[$value] ?? (string) $value;
    }

    public static function mula(RentalContract $contract): string
    {
        if (! $contract->usesPlaceholderContractDates() && $contract->tarikh_mula) {
            return $contract->tarikh_mula->format('d/m/Y');
        }

        return $contract->tarikh_mula_tawaran?->format('d/m/Y') ?? '–';
    }

    public static function tamat(RentalContract $contract): string
    {
        return $contract->contractEndDate()?->format('d/m/Y') ?? '–';
    }

    public static function bakiHari(RentalContract $contract): ?int
    {
        return $contract->daysUntilContractEnd();
    }

    public static function isBakiHariKritikal(RentalContract $contract): bool
    {
        $days = self::bakiHari($contract);

        return $days !== null && $days < RentalContract::REMINDER_THREE_MONTH_DAYS;
    }

    public static function isBakiHariAmaran(RentalContract $contract): bool
    {
        $days = self::bakiHari($contract);

        return $days !== null
            && $days >= RentalContract::REMINDER_THREE_MONTH_DAYS
            && $days < RentalContract::REMINDER_EIGHT_MONTH_DAYS;
    }

    public static function keluasan(RentalContract $contract): string
    {
        if ($contract->keluasan_mp === null) {
            return '–';
        }

        $area = (float) $contract->keluasan_mp;
        $decimals = abs($area - round($area)) < 0.001 ? 0 : 2;

        return number_format($area, $decimals);
    }

    public static function kadarSewa(RentalContract $contract): string
    {
        $amount = $contract->kadar_sewa_bulanan ?? $contract->premise?->kadar_sewa;

        if ($amount === null) {
            return '–';
        }

        return 'RM'.number_format((float) $amount, 2);
    }

    public static function rumusan(RentalContract $contract): string
    {
        $notes = $contract->applicationFormNotes();

        if (filled($notes['rumusan_status'])) {
            return (string) $notes['rumusan_status'];
        }

        if (filled($contract->peringkat_proses)) {
            return (string) $contract->peringkat_proses;
        }

        if ($contract->isHqApproved()) {
            return 'Kontrak Aktif';
        }

        return match ($contract->workflow_tahap) {
            RentalContract::WORKFLOW_SEMAKAN_PUU => 'Semakan PUU',
            RentalContract::WORKFLOW_PINDAAN_BERDASARKAN_PUU => 'Pindaan Berdasarkan PUU',
            RentalContract::WORKFLOW_PENYEDIAAN_DRAF_PERJANJIAN => 'Penyediaan Draf Perjanjian',
            RentalContract::WORKFLOW_DRAF_PERJANJIAN_LULUS => 'Draf Perjanjian Lulus',
            RentalContract::WORKFLOW_DRAF_DIKEMBALIKAN_HQ => 'Dokumen dikembalikan ke Cawangan Pembangunan',
            RentalContract::WORKFLOW_MATI_SETEM => 'Mati Setem',
            RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ => 'Menunggu Semakan Ibu Pejabat',
            RentalContract::WORKFLOW_MENUNGGU_PROCEED_NEGERI => 'Menunggu Tindakan Negeri',
            default => $contract->workflowLabel(),
        };
    }

    public static function tindakan(RentalContract $contract): string
    {
        $notes = $contract->applicationFormNotes();

        if (filled($notes['tindakan'])) {
            return (string) $notes['tindakan'];
        }

        if ($contract->isHqApproved()) {
            return '–';
        }

        return match ($contract->workflow_tahap) {
            RentalContract::WORKFLOW_SEMAKAN_PUU,
            RentalContract::WORKFLOW_PINDAAN_BERDASARKAN_PUU => 'PUU',
            RentalContract::WORKFLOW_PENYEDIAAN_DRAF_PERJANJIAN,
            RentalContract::WORKFLOW_DRAF_DIKEMBALIKAN_HQ,
            RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_HQ => 'Cawangan Pembangunan',
            RentalContract::WORKFLOW_MATI_SETEM,
            RentalContract::WORKFLOW_DRAF_PERJANJIAN_LULUS,
            RentalContract::WORKFLOW_MENUNGGU_PROCEED_NEGERI => 'Negeri',
            default => '–',
        };
    }

    public static function title(?string $negeri): string
    {
        if (filled($negeri)) {
            return 'Laporan Kontrak AADK Negeri '.$negeri;
        }

        return 'Laporan Kontrak AADK Mengikut Negeri';
    }

    /**
     * @param  Collection<int, ActivityLog>  $activityLogs
     * @return list<string>
     */
    public static function chronologyLines(RentalContract $contract, Collection $activityLogs): array
    {
        /** @var list<array{at: int, text: string}> $entries */
        $entries = [];

        foreach ($contract->processLogs as $log) {
            $text = self::formatLine($log->tarikh_tindakan, self::processLogText($log), false);

            if ($text !== '') {
                $entries[] = [
                    'at' => $log->tarikh_tindakan?->startOfDay()->getTimestamp() ?? 0,
                    'text' => $text,
                ];
            }
        }

        foreach ($activityLogs as $log) {
            $text = self::formatLine($log->created_at, (string) $log->description, true);

            if ($text !== '') {
                $entries[] = [
                    'at' => $log->created_at?->getTimestamp() ?? 0,
                    'text' => $text,
                ];
            }
        }

        $checklist = is_array($contract->hq_jrp_checklist) ? $contract->hq_jrp_checklist : [];
        $dates = is_array($checklist['dates'] ?? null) ? $checklist['dates'] : [];

        foreach (HqJrpChecklist::definitions() as $key => $label) {
            if (! filter_var($checklist[$key] ?? false, FILTER_VALIDATE_BOOLEAN)) {
                continue;
            }

            $parsed = self::parseDate($dates[$key] ?? null);

            if ($parsed === null) {
                continue;
            }

            $entries[] = [
                'at' => $parsed->copy()->startOfDay()->getTimestamp(),
                'text' => self::formatLine($parsed, $label, false),
            ];
        }

        usort($entries, function (array $left, array $right): int {
            return [$left['at'], $left['text']] <=> [$right['at'], $right['text']];
        });

        $lines = [];

        foreach ($entries as $entry) {
            if (in_array($entry['text'], $lines, true)) {
                continue;
            }

            $lines[] = $entry['text'];
        }

        return $lines;
    }

    private static function processLogText(ProcessLog $log): string
    {
        if (filled($log->keterangan)) {
            return (string) $log->keterangan;
        }

        return (string) $log->jenis_tindakan;
    }

    private static function formatLine(?Carbon $date, string $text, bool $hasTime): string
    {
        $text = trim($text);

        if ($text === '') {
            return '';
        }

        if ($date === null) {
            return $text;
        }

        $formatted = $hasTime
            ? $date->copy()->timezone(config('app.timezone'))->format('d/m/Y')
            : $date->format('d/m/Y');

        return $formatted.' – '.$text;
    }

    private static function parseDate(mixed $value): ?Carbon
    {
        if (! filled($value)) {
            return null;
        }

        try {
            return Carbon::parse((string) $value);
        } catch (\Throwable) {
            return null;
        }
    }
}
