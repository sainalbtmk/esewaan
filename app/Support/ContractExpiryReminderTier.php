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
            self::EightMonth => 'E-Sewaan AADK: Peringatan Kontrak Bawah 8 Bulan',
            self::FiveMonth => 'E-Sewaan AADK: Peringatan Tegas Kontrak Bawah 5 Bulan',
            self::ThreeMonth => 'E-Sewaan AADK: AMARAN Kontrak Baki 3 Bulan',
        };
    }

    public function heading(): string
    {
        return match ($this) {
            self::EightMonth => 'Peringatan: Kontrak Sewaan Bawah 8 Bulan',
            self::FiveMonth => 'Peringatan Tegas: Kontrak Sewaan Bawah 5 Bulan',
            self::ThreeMonth => 'AMARAN: Kontrak Sewaan Baki 3 Bulan',
        };
    }

    public function intro(): string
    {
        return match ($this) {
            self::EightMonth => 'Kontrak sewaan berikut berbaki kurang daripada 8 bulan sebelum tamat. Sila rancang tindakan lanjutan atau pindah premis.',
            self::FiveMonth => 'Ini ialah peringatan tegas. Kontrak sewaan berikut berbaki kurang daripada 5 bulan. Sila ambil tindakan lanjutan atau pindah dengan segera.',
            self::ThreeMonth => 'AMARAN. Kontrak sewaan berikut berbaki 3 bulan atau kurang. Sila lengkapkan permohonan lanjutan atau pindah, kemudian tekan Hantar ke Ibu Pejabat dan sahkan Ya, Hantar.',
        };
    }

    public function footnote(): string
    {
        return match ($this) {
            self::EightMonth => 'Emel peringatan ini dihantar sebanyak 3 kali dalam 3 hari kepada pentadbir negeri yang terlibat. Penghantaran berhenti apabila baki tempoh kontrak mencecah 5 bulan.',
            self::FiveMonth => 'Emel peringatan tegas ini dihantar setiap hari Isnin kepada pentadbir negeri yang terlibat. Penghantaran berhenti apabila baki tempoh kontrak mencecah 3 bulan.',
            self::ThreeMonth => 'Emel amaran ini dihantar setiap hari Isnin dan Rabu kepada pentadbir negeri yang terlibat, dengan salinan kepada Ibu Pejabat. Penghantaran berhenti selepas permohonan dihantar ke Ibu Pejabat.',
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
