<?php

namespace Database\Seeders;

use App\Models\AadkDaerah;
use Illuminate\Database\Seeder;

class AadkDaerahSeeder extends Seeder
{
    public function run(): void
    {
        foreach (require database_path('data/aadk_daerah.php') as $row) {
            AadkDaerah::query()->updateOrCreate(
                [
                    'negeri' => $row['negeri'],
                    'daerah' => $row['daerah'],
                ],
                $row,
            );
        }
    }
}
