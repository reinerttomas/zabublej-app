<?php

declare(strict_types=1);

namespace App\Builders;

/**
 * @extends \App\Builders\Builder<\App\Models\Event>
 */
final class EventBuilder extends Builder
{
    public function search(string $value): void
    {
        $value = "%$value%";

        $this->where('name', 'like', $value)
            ->orWhere('location', 'like', $value);
    }
}
