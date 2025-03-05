<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Tomáš Reinert',
            'email' => 'reinerttomas@gmail.com',
            'email_verified_at' => now(),
        ]);

        User::factory(25)->create();
    }
}
