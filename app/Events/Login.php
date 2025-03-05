<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\User;

final readonly class Login
{
    public function __construct(
        public User $user,
    ) {}
}
