<?php

declare(strict_types=1);

namespace App\States\Events;

use App\Enums\EventStatus;

final class EventDraftState extends BaseEventState
{
    public function published(): void
    {
        $this->event->status = EventStatus::Published;
    }

    public function cancelled(): void
    {
        $this->event->status = EventStatus::Cancelled;
    }
}
