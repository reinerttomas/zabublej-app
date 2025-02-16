<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'first_name' => 'Tomas',
            'last_name' => 'Reinert',
            'email' => 'reinerttomas@gmail.com',
        ]);

        $this->call([
            UserSeeder::class,
            EventSeeder::class,
        ]);
    }
}
