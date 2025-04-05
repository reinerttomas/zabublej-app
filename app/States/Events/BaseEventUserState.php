<?php

declare(strict_types=1);

namespace App\States\Events;

use App\Contracts\Events\EventUserState;
use App\Exceptions\NotImplementedException;
use App\Models\EventAttendance;

abstract class BaseEventUserState implements EventUserState
{
    public function __construct(public readonly EventAttendance $eventUser) {}

    public function pending(): void
    {
        throw new NotImplementedException();
    }

    public function approved(): void
    {
        throw new NotImplementedException();
    }

    public function rejected(): void
    {
        throw new NotImplementedException();
    }
}
