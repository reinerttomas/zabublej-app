<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Event;
use App\Notifications\Events\EventCancelledNotification;
use App\Notifications\Events\EventUpdatedNotification;
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
        // Event was cancelled
        if ($event->wasChanged('status') && ($event->status->isCancelled() && $event->status->notEqual($event->getOriginal('status')))) {
            $this->notifyUsers($event, new EventCancelledNotification($event));
        }

        if (
            $event->wasChanged('name')
            || $event->wasChanged('description')
            || $event->wasChanged('starts_at')
            || $event->wasChanged('location')
            || $event->wasChanged('contact_person')
            || $event->wasChanged('contact_phone')
            || $event->wasChanged('note')
        ) {
            $this->notifyUsers($event, new EventUpdatedNotification($event));
        }
    }

    private function notifyUsers(Event $event, Notification $notification): void
    {
        $event->users->each(function ($user) use ($notification): void {
            $user->notify($notification);
        });
    }
}
