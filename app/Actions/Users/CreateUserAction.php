<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Models\User;

final readonly class CreateUserAction
{
    public function execute(array $data): User
    {
        return User::create($data);
    }
}
