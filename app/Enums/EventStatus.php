<?php

declare(strict_types=1);

namespace App\Enums;

use App\Contracts\Comparable;

/**
 * @implements Comparable<$this>
 */
enum EventStatus: int implements Comparable
{
    use HasComparable;

    case Draft = 1;
    case Published = 2;
    case Cancelled = 3;
    case Completed = 4;

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Návrh',
            self::Published => 'Zvejněno',
            self::Cancelled => 'Zrušeno',
            self::Completed => 'Hotovo',
        };
    }

    public function badge(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Published => 'yellow',
            self::Cancelled => 'red',
            self::Completed => 'green',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'bg-zinc-600 dark:bg-zinc-600',
            self::Published => 'bg-yellow-500 dark:bg-yellow-400',
            self::Cancelled => 'bg-red-500 dark:bg-red-600',
            self::Completed => 'bg-green-500 dark:bg-green-600',
        };
    }

    public function isDraft(): bool
    {
        return $this === self::Draft;
    }

    public function isPublished(): bool
    {
        return $this === self::Published;
    }

    public function isCancelled(): bool
    {
        return $this === self::Cancelled;
    }

    public function isCompleted(): bool
    {
        return $this === self::Completed;
    }
}
