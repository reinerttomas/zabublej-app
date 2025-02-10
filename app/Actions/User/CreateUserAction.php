<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;

final readonly class CreateUserAction
{
    public function execute(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'email_verified_at' => now(),
        ]);
    }
}
