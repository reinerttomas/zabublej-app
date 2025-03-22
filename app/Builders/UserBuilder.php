<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\User;
use Closure;

/**
 * @extends Builder<User>
 */
final class UserBuilder extends Builder
{
    public function search(string $value): self
    {
        $value = "%$value%";

        return $this->where('name', 'like', $value)
            ->orWhere('email', 'like', $value)
            ->orWhere('phone', 'like', $value);
    }

    public function whereHasEvents(Closure $condition): self
    {
        return $this->whereHas('events', $condition);
    }

    public function whereDoesntHaveEvents(Closure $condition): self
    {
        return $this->whereDoesntHave('events', $condition);
    }

    public function whereHasRole(Closure $condition): self
    {
        return $this->whereHas('roles', $condition);
    }

    public function whereEmail(string $email): self
    {
        return $this->where('email', $email);
    }
}
