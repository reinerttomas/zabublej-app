<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Event;
use App\Services\EventService;

final readonly class EventObserver
{
    public function __construct(
        private EventService $eventService,
    ) {}

    public function saving(Event $event): void
    {
        if (! $event->wasRecentlyCreated && $event->isDirty('status')) {
            $event->fill(
                $this->eventService->prepareAttributesForStatusChange($event->status, $event)
            );
        }
    }
}
