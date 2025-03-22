<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Event;
use App\Notifications\EventAssignmentNotification;
use App\Notifications\EventCancelledNotification;
use App\Services\EventService;
use Illuminate\Notifications\Notification;

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

    public function updated(Event $event): void
    {
        if ($event->wasChanged('status')) {
            // Event was published

            if (
                $event->status->isPublished()
                && $event->status->notEqual($event->getOriginal('status'))
            ) {
                $this->notifyUsers($event, new EventAssignmentNotification($event));
            }

            // Event was cancelled
            if (
                $event->status->isCancelled()
                && $event->status->notEqual($event->getOriginal('status'))
            ) {
                $this->notifyUsers($event, new EventCancelledNotification($event));
            }
        }
    }

    private function notifyUsers(Event $event, Notification $notification): void
    {
        $event->users->each(function ($user) use ($notification): void {
            $user->notify($notification);
        });
    }
}
