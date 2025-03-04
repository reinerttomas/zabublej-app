<?php

declare(strict_types=1);

namespace App\Enums\Database;

enum Direction: string
{
    case ASC = 'asc';
    case DESC = 'desc';

    public function invert(): self
    {
        return match ($this) {
            self::ASC => self::DESC,
            self::DESC => self::ASC,
        };
    }
}
