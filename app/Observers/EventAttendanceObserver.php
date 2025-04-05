<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\EventAttendanceStatus;
use App\Enums\Permission;
use App\Models\EventAttendance;
use App\Models\User;
use App\Notifications\Events\EventAttendanceConfirmedNotification;
use App\Notifications\Events\EventAttendancePendingNotification;
use App\Notifications\Events\EventAttendanceRejectedNotification;
use App\Notifications\Events\EventAttendanceRequestNotification;
use App\Support\Facades\Auth;

final readonly class EventAttendanceObserver
{
    public function saving(EventAttendance $eventAttendance): void
    {
        $eventAttendance->status ?? $eventAttendance->setStatus(EventAttendanceStatus::Pending);

        $data = match ($eventAttendance->status) {
            EventAttendanceStatus::Pending => [
                'processor_id' => null,
                'processed_at' => null,
            ],
            EventAttendanceStatus::Confirmed, EventAttendanceStatus::Rejected => [
                'processor_id' => Auth::userOrFail()->id,
                'processed_at' => now(),
            ],
        };

        $eventAttendance->fill($data);
    }

    public function saved(EventAttendance $eventAttendance): void
    {
        if ($eventAttendance->event->status->isPublished()) {
            match ($eventAttendance->status) {
                EventAttendanceStatus::Pending => $this->notifyEventAttendancePending($eventAttendance),
                EventAttendanceStatus::Confirmed => $this->notifyEventAttendanceConfirmed($eventAttendance),
                EventAttendanceStatus::Rejected => $this->notifyEventAttendanceRejected($eventAttendance),
            };
        }
    }

    public function created(EventAttendance $eventAttendance): void
    {
        if ($eventAttendance->status->isPending()) {
            User::query()
                ->permission(Permission::UpdateEventAttendance)
                ->get()
                ->each(function (User $user) use ($eventAttendance): void {
                    $user->notify(new EventAttendanceRequestNotification($eventAttendance));
                });
        }
    }

    private function notifyEventAttendancePending(EventAttendance $eventAttendance): void
    {
        $eventAttendance->user->notify(
            new EventAttendancePendingNotification($eventAttendance)
        );
    }

    private function notifyEventAttendanceConfirmed(EventAttendance $eventAttendance): void
    {
        $eventAttendance->user->notify(
            new EventAttendanceConfirmedNotification($eventAttendance)
        );
    }

    private function notifyEventAttendanceRejected(EventAttendance $eventAttendance): void
    {
        $eventAttendance->user->notify(
            new EventAttendanceRejectedNotification($eventAttendance)
        );
    }
}
