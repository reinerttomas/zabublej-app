<?php

declare(strict_types=1);

namespace App\Enums;

use App\Contracts\Comparable;

/**
 * @implements Comparable<$this>
 */
enum EventAttendanceStatus: int implements Comparable
{
    use HasComparable;

    case Pending = 1;
    case Confirmed = 2;
    case Rejected = 3;

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Čeká na schválení',
            self::Confirmed => 'Schváleno',
            self::Rejected => 'Zamítnuto',
        };
    }

    public function badge(): string
    {
        return match ($this) {
            self::Pending => 'yellow',
            self::Confirmed => 'green',
            self::Rejected => 'red',
        };
    }

    public function isPending(): bool
    {
        return $this === self::Pending;
    }

    public function isConfirm(): bool
    {
        return $this === self::Confirmed;
    }

    public function isRejected(): bool
    {
        return $this === self::Rejected;
    }
}
