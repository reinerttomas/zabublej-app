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

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Published => 'green',
            self::Cancelled => 'red',
            self::Completed => 'blue',
        };
    }
}
