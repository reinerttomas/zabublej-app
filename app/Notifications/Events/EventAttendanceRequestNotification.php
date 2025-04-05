<?php

declare(strict_types=1);

namespace App\Notifications\Events;

use App\Enums\EventAttendanceStatus;
use App\Models\EventAttendance;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

final class EventAttendanceRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly EventAttendance $eventAttendance
    ) {}

    /**
     * @return list<string>
     */
    public function via(User $user): array
    {
        return ['mail'];
    }

    public function toMail(User $organizer): MailMessage
    {
        $event = $this->eventAttendance->event;
        $worker = $this->eventAttendance->user;

        $confirmUrl = URL::temporarySignedRoute(
            'event-attendances.status',
            now()->addDays(7),
            [
                'eventAttendance' => $this->eventAttendance->id,
                'status' => EventAttendanceStatus::Confirmed->value,
            ]
        );

        $rejectUrl = URL::temporarySignedRoute(
            'event-attendances.status',
            now()->addDays(7),
            [
                'eventAttendance' => $this->eventAttendance->id,
                'status' => EventAttendanceStatus::Rejected->value,
            ]
        );

        return (new MailMessage)
            ->subject('Nová přihláška čeká na schválení')
            ->greeting('Ahoj, ' . $organizer->name)
            ->line('Nová přihláška na akci čeká na schválení.')
            ->line('Pracovník: ' . $worker->name)
            ->line('Název: ' . $event->name)
            ->line('Datum: ' . $event->start_at->translatedFormatDate())
            ->line('Čas: ' . $event->start_at->translatedFormatTime())
            ->when(
                $event->location,
                fn (MailMessage $message) => $message->line('Místo: ' . $event->location)
            )
            ->action('Schválit přihlášku', $confirmUrl);
    }
}
