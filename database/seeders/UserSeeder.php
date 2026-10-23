<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\MalaysianStates;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public static function negeriAdminEmail(string $negeri): string
    {
        return str($negeri)->lower()->replace('w.p. ', '')->slug().'@esewa.test';
    }

    public function run(): void
    {
        $this->seedUser(
            email: 'admin@esewa.test',
            name: 'Ibu Pejabat',
            role: 'admin_hq',
            negeri: null,
        );

        $this->seedUser(
            email: 'negeri@esewa.test',
            name: 'Negeri Johor',
            role: 'admin_negeri',
            negeri: 'Selangor',
        );

        foreach (MalaysianStates::values() as $negeri) {
            $this->seedUser(
                email: self::negeriAdminEmail($negeri),
                name: 'Pentadbir '.$negeri,
                role: 'admin_negeri',
                negeri: $negeri,
            );
        }
    }

    private function seedUser(string $email, string $name, string $role, ?string $negeri): void
    {
        $user = User::query()->firstOrNew(['email' => $email]);
        $user->fill([
            'name' => $name,
            'password' => 'password',
            'negeri' => $negeri,
        ]);
        $user->forceFill([
            'role' => $role,
            'is_active' => true,
        ])->save();
    }
}
