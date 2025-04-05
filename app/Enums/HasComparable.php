<?php

declare(strict_types=1);

namespace App\Enums;

use App\Contracts\Comparable;

trait HasComparable
{
    public function equal(Comparable $other): bool
    {
        return $this === $other;
    }

    public function notEqual(Comparable $other): bool
    {
        return $this !== $other;
    }

    public function equalAll(array $others): bool
    {
        return collect($others)->every(fn (Comparable $other) => $this->equal($other));
    }

    public function notEqualAll(array $others): bool
    {
        return collect($others)->every(fn (Comparable $other) => $this->notEqual($other));
    }
}
