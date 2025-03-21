<?php

declare(strict_types=1);

namespace App\States\Events;

use App\Enums\EventStatus;

final class EventPublishedState extends BaseEventState
{
    public function completed(): void
    {
        $this->event->status = EventStatus::Completed;
    }

    public function cancelled(): void
    {
        $this->event->status = EventStatus::Cancelled;
    }
}
