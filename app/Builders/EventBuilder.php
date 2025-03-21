<?php

declare(strict_types=1);

namespace App\Builders;

use Closure;

/**
 * @extends Builder<\App\Models\Event>
 */
final class EventBuilder extends Builder
{
    public function search(string $value): void
    {
        $value = "%$value%";

        $this->where('name', 'like', $value)
            ->orWhere('location', 'like', $value);
    }

    public function whereHasUsers(Closure $condition): self
    {
        return $this->whereHas('users', $condition);
    }
}
