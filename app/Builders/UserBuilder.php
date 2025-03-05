<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\User;

/**
 * @extends Builder<User>
 */
final class UserBuilder extends Builder
{
    public function search(string $value): void
    {
        $value = "%$value%";

        $this->where('name', 'like', $value)
            ->orWhere('email', 'like', $value)
            ->orWhere('phone', 'like', $value);
    }
}
