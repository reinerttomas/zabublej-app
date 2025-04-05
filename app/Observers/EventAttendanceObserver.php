<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\EventAttendanceStatus;
use App\Models\EventAttendance;
use App\Notifications\Events\EventAttendanceConfirmedNotification;
use App\Notifications\Events\EventAttendancePendingNotification;
use App\Notifications\Events\EventAttendanceRejectedNotification;
use App\Support\Facades\Auth;

final readonly class EventAttendanceObserver
{
    public function saving(EventAttendance $eventAttendance): void
    {
        $eventAttendance->status ?? $eventAttendance->setStatus(EventAttendanceStatus::Pending);

        // Set the processor and processedAt fields if the status is approved or rejected
        if ($eventAttendance->status->isApprovedOrRejected()) {
            $eventAttendance->fill([
                'processor_id' => Auth::userOrFail()->id,
                'processed_at' => now(),
            ]);
        }
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

    private function notifyEventAttendancePending(EventAttendance $eventAttendance): void
    {
        $eventAttendance->user->notify(
            new EventAttendancePendingNotification($eventAttendance->event)
        );
    }

    private function notifyEventAttendanceConfirmed(EventAttendance $eventAttendance): void
    {
        $eventAttendance->user->notify(
            new EventAttendanceConfirmedNotification($eventAttendance->event)
        );
    }

    private function notifyEventAttendanceRejected(EventAttendance $eventAttendance): void
    {
        $eventAttendance->user->notify(
            new EventAttendanceRejectedNotification($eventAttendance->event)
        );
    }
}
