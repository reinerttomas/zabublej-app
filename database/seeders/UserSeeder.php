<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

final class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->superAdmin()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
        ]);

        User::factory()->admin()->create([
            'name' => 'User Admin',
            'email' => 'admin@example.com',
        ]);

        User::factory()->staff()->create([
            'name' => 'User Staff',
            'email' => 'staff@example.com',
        ]);

        User::factory()->staff()->count(5)->create();
    }
}
