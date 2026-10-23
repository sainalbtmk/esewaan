<?php

namespace Database\Seeders;

use App\Models\Premise;
use App\Models\RentalContract;
use App\Models\User;
use App\Support\ApplicationCategories;
use App\Support\BuildingTypes;
use App\Support\MalaysianStates;
use App\Support\PremiseStatuses;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use RuntimeException;

class PremisSewaanSeeder extends Seeder
{
    /**
     * Setiap negeri mendapat lima premis sewaan aktif:
     * baki 2 bulan (kurang 3), 4 bulan (kurang 5), 6 bulan (kurang 8),
     * serta 12 bulan dan 18 bulan (lebih 8).
     *
     * @var list<array{months: int, label: string, rent: float, area: float, building: string, bank: string}>
     */
    private const SAMPLES = [
        ['months' => 2, 'label' => 'Kurang 3 bulan', 'rent' => 1800, 'area' => 850, 'building' => BuildingTypes::KOMERSIAL, 'bank' => 'Maybank'],
        ['months' => 4, 'label' => 'Kurang 5 bulan', 'rent' => 2400, 'area' => 1200, 'building' => BuildingTypes::KOMPLEKS_KERAJAAN, 'bank' => 'CIMB Bank'],
        ['months' => 6, 'label' => 'Kurang 8 bulan', 'rent' => 2800, 'area' => 1500, 'building' => BuildingTypes::RUMAH_KEDIAMAN, 'bank' => 'Bank Islam'],
        ['months' => 12, 'label' => 'Lebih 8 bulan', 'rent' => 3200, 'area' => 1800, 'building' => BuildingTypes::KOMERSIAL, 'bank' => 'RHB Bank'],
        ['months' => 18, 'label' => 'Lebih 8 bulan', 'rent' => 4100, 'area' => 2200, 'building' => BuildingTypes::KOMPLEKS_KERAJAAN, 'bank' => 'Public Bank'],
    ];

    public function run(): void
    {
        foreach ($this->states() as $negeri => $state) {
            $admin = User::query()
                ->where('email', UserSeeder::negeriAdminEmail($negeri))
                ->where('role', 'admin_negeri')
                ->firstOrFail();

            foreach (self::SAMPLES as $index => $sample) {
                $this->seedSample($negeri, $state, $admin, $index, $sample);
            }
        }
    }

    /**
     * @param  array{code: string, lat: float, long: float, daerah: list<string>}  $state
     * @param  array{months: int, label: string, rent: float, area: float, building: string, bank: string}  $sample
     */
    private function seedSample(string $negeri, array $state, User $admin, int $index, array $sample): void
    {
        $number = $index + 1;
        $daerah = $state['daerah'][$index];
        $reference = sprintf('DUMMY/%s/%02d', $state['code'], $number);
        $end = Carbon::today()->addMonths($sample['months']);
        $start = $end->copy()->subYears(2);

        $contract = RentalContract::withTrashed()->firstOrNew([
            'no_fail_rujukan' => $reference,
        ]);

        if ($contract->trashed()) {
            $contract->restore();
        }

        $premise = $contract->exists ? $contract->premise : null;
        $premise ??= new Premise;

        $premise->fill([
            'nama_ptj' => 'AADK '.$daerah.' ('.$sample['label'].')',
            'negeri' => $negeri,
            'daerah' => $daerah,
            'alamat_penuh' => 'Lot '.$number.', Jalan AADK, '.$daerah.', '.$negeri,
            'jenis_bangunan' => $sample['building'],
            'status_premis' => PremiseStatuses::RENTED,
            'tarikh_mula' => $start->toDateString(),
            'tarikh_akhir' => $end->toDateString(),
            'kadar_sewa' => $sample['rent'],
            'koordinat_lat' => $state['lat'],
            'koordinat_long' => $state['long'],
            'nama_pemilik' => 'Pemilik '.$daerah,
            'no_hak_milik' => sprintf('HSD %s %04d', $state['code'], $number),
            'status_pemilikan' => 'milik persendirian',
            'catatan_hak_milik' => 'Data dummy untuk semakan baki tempoh '.$sample['label'].'.',
            'alamat_pemilik' => 'No. '.$number.', Jalan Pemilik, '.$daerah.', '.$negeri,
            'no_telefon_pemilik' => sprintf('012-3%02d%04d', $number, $index + 1000),
            'bank_pemilik' => $sample['bank'],
            'no_akaun_bank' => sprintf('800%09d', ($index + 1) * 1000 + $number),
        ])->save();

        $contract->commit([
            'premise_id' => $premise->id,
            'submitted_by_user_id' => $admin->id,
            'admin_negeri_user_id' => $admin->id,
            'no_fail_rujukan' => $reference,
            'tarikh_mula' => $start->toDateString(),
            'tarikh_tamat' => $end->toDateString(),
            'kadar_sewa_bulanan' => $sample['rent'],
            'keluasan_mp' => $sample['area'],
            'status_aktif' => 'aktif',
            'peringkat_proses' => null,
            'workflow_tahap' => RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
            'kategori_permohonan' => ApplicationCategories::BARU,
            'hq_approved_at' => $contract->hq_approved_at ?? $start->copy(),
            'superseded_at' => null,
            'superseded_by_contract_id' => null,
        ]);
    }

    /**
     * @return array<string, array{code: string, lat: float, long: float, daerah: list<string>}>
     */
    private function states(): array
    {
        $states = [
            'Johor' => ['code' => 'JHR', 'lat' => 1.4927, 'long' => 103.7414, 'daerah' => ['Johor Bahru', 'Batu Pahat', 'Muar', 'Kluang', 'Kota Tinggi']],
            'Kedah' => ['code' => 'KDH', 'lat' => 6.1184, 'long' => 100.3685, 'daerah' => ['Kota Setar', 'Kuala Muda', 'Kulim', 'Langkawi', 'Kubang Pasu']],
            'Kelantan' => ['code' => 'KTN', 'lat' => 6.1254, 'long' => 102.2381, 'daerah' => ['Kota Bharu', 'Pasir Mas', 'Tanah Merah', 'Kuala Krai', 'Tumpat']],
            'Melaka' => ['code' => 'MLK', 'lat' => 2.1896, 'long' => 102.2501, 'daerah' => ['Melaka Tengah', 'Alor Gajah', 'Jasin', 'Masjid Tanah', 'Bandaraya Melaka']],
            'Negeri Sembilan' => ['code' => 'NSN', 'lat' => 2.7258, 'long' => 101.9424, 'daerah' => ['Seremban', 'Port Dickson', 'Jempol', 'Tampin', 'Kuala Pilah']],
            'Pahang' => ['code' => 'PHG', 'lat' => 3.8077, 'long' => 103.3260, 'daerah' => ['Kuantan', 'Temerloh', 'Bentong', 'Jerantut', 'Pekan']],
            'Perak' => ['code' => 'PRK', 'lat' => 4.5975, 'long' => 101.0901, 'daerah' => ['Kinta', 'Larut Matang', 'Hilir Perak', 'Kampar', 'Manjung']],
            'Perlis' => ['code' => 'PLS', 'lat' => 6.4449, 'long' => 100.2048, 'daerah' => ['Kangar', 'Arau', 'Padang Besar', 'Kuala Perlis', 'Beseri']],
            'Pulau Pinang' => ['code' => 'PNG', 'lat' => 5.4141, 'long' => 100.3288, 'daerah' => ['Timur Laut', 'Barat Daya', 'Seberang Perai Tengah', 'Seberang Perai Utara', 'Seberang Perai Selatan']],
            'Sabah' => ['code' => 'SBH', 'lat' => 5.9804, 'long' => 116.0735, 'daerah' => ['Kota Kinabalu', 'Sandakan', 'Tawau', 'Lahad Datu', 'Keningau']],
            'Sarawak' => ['code' => 'SWK', 'lat' => 1.5533, 'long' => 110.3592, 'daerah' => ['Kuching', 'Miri', 'Sibu', 'Bintulu', 'Sri Aman']],
            'Selangor' => ['code' => 'SGR', 'lat' => 3.0738, 'long' => 101.5183, 'daerah' => ['Petaling', 'Klang', 'Hulu Langat', 'Gombak', 'Sepang']],
            'Terengganu' => ['code' => 'TRG', 'lat' => 5.3117, 'long' => 103.1324, 'daerah' => ['Kuala Terengganu', 'Kemaman', 'Dungun', 'Besut', 'Marang']],
            'W.P. Kuala Lumpur' => ['code' => 'WPKL', 'lat' => 3.1390, 'long' => 101.6869, 'daerah' => ['Cheras', 'Kepong', 'Setapak', 'Wangsa Maju', 'Bukit Bintang']],
            'W.P. Labuan' => ['code' => 'WPL', 'lat' => 5.2831, 'long' => 115.2308, 'daerah' => ['Victoria', 'Ranca-Ranca', 'Layang-Layangan', 'Sungai Keling', 'Patau-Patau']],
            'W.P. Putrajaya' => ['code' => 'WPP', 'lat' => 2.9264, 'long' => 101.6964, 'daerah' => ['Presint 1', 'Presint 2', 'Presint 3', 'Presint 4', 'Presint 5']],
        ];

        $missing = array_values(array_diff(MalaysianStates::values(), array_keys($states)));

        if ($missing !== []) {
            throw new RuntimeException('Daerah dummy belum ditetapkan untuk: '.implode(', ', $missing));
        }

        foreach ($states as $negeri => $state) {
            if (count($state['daerah']) !== count(self::SAMPLES)) {
                throw new RuntimeException('Bilangan daerah untuk '.$negeri.' tidak sepadan dengan bilangan sampel.');
            }
        }

        return $states;
    }
}
