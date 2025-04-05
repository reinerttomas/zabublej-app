<?php

declare(strict_types=1);

namespace App\States\Events;

use App\Contracts\Events\EventState;
use App\Exceptions\NotImplementedException;
use App\Models\Event;

abstract class BaseEventState implements EventState
{
    public function __construct(public readonly Event $event) {}

    public function published(): void
    {
        throw new NotImplementedException();
    }

    public function cancelled(): void
    {
        throw new NotImplementedException();
    }

    public function completed(): void
    {
        throw new NotImplementedException();
    }
}
