<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Console\Command;

final class UserInitCommand extends Command
{
    protected $signature = 'user:init';

    protected $description = 'Command description';

    public function handle(): void
    {
        foreach ($this->getAdmins() as $admin) {
            $user = User::create($admin);
            $user->assignRole(Role::Admin);
        }

        foreach ($this->getWorkers() as $worker) {
            $user = User::create($worker);
            $user->assignRole(Role::Worker);
        }

        $this->info('Events initialized successfully.');
    }

    private function getAdmins(): iterable
    {
        yield [
            'name' => 'Tomáš Reinert',
            'email' => 'reinerttomas@gmail.com',
            'email_verified_at' => now(),
            'phone' => '+420777111222',
            'password' => 'Tomas123',
        ];

        yield [
            'name' => 'Daniel Kunášek',
            'email' => 'kunasekdaniel@gmail.com',
            'email_verified_at' => now(),
            'phone' => '+420777111222',
            'password' => 'Daniel123',
        ];
    }

    private function getWorkers(): iterable
    {
        yield [
            'name' => 'Dan Hejzlar',
            'email' => 'daniel.hejzlar@email.cz',
            'email_verified_at' => now(),
            'phone' => '+420731140028',
            'password' => 'Heslo123',
        ];

        yield [
            'name' => 'Rachel Hejzlarova',
            'email' => 'rachel.smikova@seznam.cz',
            'email_verified_at' => now(),
            'phone' => '+420725308605',
            'password' => 'Heslo123',
        ];

        yield [
            'name' => 'Jáchym Hejzlar',
            'email' => 'jachym.hejzlar@seznam.cz',
            'email_verified_at' => now(),
            'phone' => '+420731140029',
            'password' => 'Heslo123',
        ];

        yield [
            'name' => 'Jaroslav Kaňa',
            'email' => 'jaroskan@email.cz',
            'email_verified_at' => now(),
            'phone' => '+420703388151',
            'password' => 'Heslo123',
        ];

        yield [
            'name' => 'Martin Kunášek',
            'email' => 'kunasek.m@centrum.cz',
            'email_verified_at' => now(),
            'phone' => '+420607248581',
            'password' => 'Heslo123',
        ];
    }
}
