<?php

namespace App\Support;

enum ContractExpiryReminderTier: string
{
    case EightMonth = 'eight_month';
    case FiveMonth = 'five_month';
    case ThreeMonth = 'three_month';

    public function subject(): string
    {
        return match ($this) {
            self::EightMonth => 'SPEKTRA: PERINGATAN PENGHANTARAN PERMOHONAN PELANJUTAN SEWAAN RUANG PEJABAT AADK UNTUK KONTRAK YANG AKAN TAMAT DALAM TEMPOH LAPAN (8) BULAN',
            self::FiveMonth => 'SPEKTRA: Peringatan Tegas Kontrak Bawah 5 Bulan',
            self::ThreeMonth => 'SPEKTRA: AMARAN Senarai Ruang Pejabat Hampir Tamat Tempoh dan Perlu Tindakan SEGERA',
        };
    }

    public function heading(): string
    {
        return match ($this) {
            self::EightMonth => 'Peringatan: Kontrak Sewaan Bawah 8 Bulan',
            self::FiveMonth => 'Peringatan Tegas: Kontrak Sewaan Bawah 5 Bulan',
            self::ThreeMonth => 'AMARAN: Senarai Ruang Pejabat Hampir Tamat Tempoh dan Perlu Tindakan SEGERA',
        };
    }

    public function intro(): string
    {
        return match ($this) {
            self::EightMonth => 'Kontrak sewaan berikut berbaki kurang daripada 8 bulan sebelum tamat. Sila rancang tindakan lanjutan atau pindah premis.',
            self::FiveMonth => 'Ini ialah peringatan tegas. Kontrak sewaan berikut berbaki kurang daripada 5 bulan. Sila ambil tindakan lanjutan atau pindah dengan segera.',
            self::ThreeMonth => 'AMARAN untuk negeri yang terlibat sahaja. Ruang pejabat berikut berbaki 3 bulan atau kurang dan memerlukan tindakan Pindah atau Lanjutan.',
        };
    }

    public function footnote(): string
    {
        return match ($this) {
            self::EightMonth => 'Emel peringatan ini dihantar sebanyak 3 kali dalam 3 hari kepada pentadbir negeri yang terlibat. Penghantaran berhenti apabila baki tempoh kontrak mencecah 5 bulan.',
            self::FiveMonth => 'Emel peringatan tegas ini dihantar setiap hari Isnin kepada pentadbir negeri yang terlibat. Penghantaran berhenti apabila baki tempoh kontrak mencecah 3 bulan.',
            self::ThreeMonth => 'Emel ini dihantar setiap hari pada jam 8:00 pagi kepada negeri yang terlibat sebagai amaran, dan senarai penuh dihantar kepada Ketua Pengarah, TKPP, PKP dan KPP. Penghantaran berhenti selepas tindakan Pindah atau Lanjutan dihantar ke Ibu Pejabat.',
        };
    }

    public function maximumSends(): ?int
    {
        return match ($this) {
            self::EightMonth => 3,
            self::FiveMonth, self::ThreeMonth => null,
        };
    }
}
