<?php

declare(strict_types=1);

namespace App\States\Events;

use App\Enums\EventStatus;

final class EventDraftState extends BaseEventState
{
    public function published(): void
    {
        $this->event->setStatus(EventStatus::Published);
    }

    public function cancelled(): void
    {
        $this->event->setStatus(EventStatus::Cancelled);
    }
}
