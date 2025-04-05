<?php

declare(strict_types=1);

namespace App\Actions\Events;

use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\User;
use App\Notifications\Events\EventAttendanceDeletedNotification;

final readonly class DeleteEventAttendanceAction
{
    public function execute(Event $event, User $user): void
    {
        EventAttendance::query()
            ->whereEventId($event->id)
            ->whereUserId($user->id)
            ->delete();

        $user->notify(
            new EventAttendanceDeletedNotification($event)
        );
    }
}
