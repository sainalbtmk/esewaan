<?php

namespace Database\Factories;

use App\Models\ContractExpiryReminderSend;
use App\Models\Premise;
use App\Models\RentalContract;
use App\Support\ContractExpiryReminderTier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContractExpiryReminderSend>
 */
class ContractExpiryReminderSendFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $premise = Premise::query()->create([
            'nama_ptj' => fake()->company(),
            'negeri' => 'Johor',
            'daerah' => 'Johor Bahru',
            'alamat_penuh' => fake()->address(),
            'nama_pemilik' => fake()->name(),
        ]);

        $contract = RentalContract::query()->create([
            'premise_id' => $premise->id,
            'tarikh_mula' => RentalContract::PLACEHOLDER_CONTRACT_DATE,
            'tarikh_tamat' => RentalContract::PLACEHOLDER_CONTRACT_DATE,
            'kadar_sewa_bulanan' => 1000,
            'status_aktif' => 'aktif',
            'workflow_tahap' => RentalContract::WORKFLOW_MENUNGGU_SEMAKAN_NEGERI,
            'sah_sehingga' => now()->addDays(200)->toDateString(),
        ]);

        return [
            'rental_contract_id' => $contract->id,
            'tier' => ContractExpiryReminderTier::EightMonth,
            'sent_on' => now()->toDateString(),
        ];
    }
}
