<?php

namespace Database\Factories;

use App\Models\AadkDaerah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AadkDaerah>
 */
class AadkDaerahFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'negeri' => 'Johor',
            'daerah' => fake()->unique()->bothify('Daerah ####'),
            'nama_pejabat' => 'AADK Daerah Ujian',
            'alamat' => fake()->address(),
            'ketua_daerah' => fake()->name(),
            'telefon' => '07-227 6900',
            'faks' => '07-223 7942',
            'emel' => fake()->unique()->safeEmail(),
            'susunan' => fake()->unique()->numberBetween(1000, 9999),
        ];
    }
}
