<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

final class CreateSuperAdminCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'user:create-super-admin';

    /**
     * @var string
     */
    protected $description = 'Create a super admin';

    public function handle(): void
    {
        $userData = $this->getData();

        if (User::query()->whereEmail($userData['email'])->exists()) {
            $this->warn('Super admin already exists.');

            return;
        }

        $user = DB::transaction(function () use ($userData): User {
            $user = User::create($userData);

            $user->assignRole(Role::SuperAdmin);

            return $user;
        });

        $this->info("Super admin {$user->email} created");
    }

    /**
     * @return array{ name: string, email: string, password: string }
     */
    private function getData(): array
    {
        return [
            'name' => 'Tomáš Reinert',
            'email' => 'reinerttomas@gmail.com',
            'password' => 'password',
        ];
    }
}
