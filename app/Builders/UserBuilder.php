<?php

declare(strict_types=1);

namespace App\Builders;

/**
 * @extends \App\Builders\Builder<\App\Models\User>
 */
final class UserBuilder extends Builder
{
    public function search(string $value): void
    {
        $value = "%$value%";

        $this->where('first_name', 'like', $value)
            ->orWhere('last_name', 'like', $value)
            ->orWhere('email', 'like', $value)
            ->orWhere('phone', 'like', $value);
    }
}
