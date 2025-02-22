<?php

declare(strict_types=1);

namespace App\Enums;

enum EventStatus: int
{
    case Draft = 0;
    case Published = 1;
    case Cancelled = 2;
    case Completed = 3;

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Published => 'Published',
            self::Cancelled => 'Cancelled',
            self::Completed => 'Completed',
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

    public function isEqual(EventStatus $status): bool
    {
        return $this->value === $status->value;
    }
}
