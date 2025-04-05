<?php

declare(strict_types=1);

namespace App\Enums;

enum EventTab: string
{
    case MyEvents = 'my';
    case AvailableEvents = 'available';

    public function isMy(): bool
    {
        return $this === self::MyEvents;
    }

    public function isAvailable(): bool
    {
        return $this === self::AvailableEvents;
    }
}
