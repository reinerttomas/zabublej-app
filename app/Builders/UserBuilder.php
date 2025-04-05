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

    public function whereHasEventAttendances(?Closure $condition = null): self
    {
        return $this->whereHas('eventAttendances', $condition);
    }

    public function whereDoesntHaveEventAttendances(?Closure $condition = null): self
    {
        return $this->whereDoesntHave('eventAttendances', $condition);
    }

    public function whereEmail(string $email): self
    {
        return $this->where('email', $email);
    }
}
