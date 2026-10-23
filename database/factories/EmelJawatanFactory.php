<?php

namespace Database\Factories;

use App\Models\EmelJawatan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EmelJawatan>
 */
class EmelJawatanFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kod' => fake()->unique()->lexify('jawatan_????'),
            'nama_jawatan' => fake()->jobTitle(),
            'email' => fake()->unique()->safeEmail(),
            'susunan' => fake()->numberBetween(10, 99),
        ];
    }
}
