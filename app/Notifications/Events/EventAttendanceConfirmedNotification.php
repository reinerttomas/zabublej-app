<?php

declare(strict_types=1);

namespace App\Notifications\Events;

use App\Models\EventAttendance;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

final class EventAttendanceConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly EventAttendance $eventAttendance,
    ) {}

    /**
     * @return list<string>
     */
    public function via(User $user): array
    {
        return ['mail'];
    }

    public function toMail(User $user): MailMessage
    {
        $event = $this->eventAttendance->event;

        return (new MailMessage)
            ->subject('Přihláška potvrzena')
            ->greeting('Ahoj, ' . $user->name)
            ->line('Tvoje přihláška na událost byla schválena.')
            ->line('Název: ' . $event->name)
            ->line('Datum: ' . $event->start_at->translatedFormatDate())
            ->line('Čas: ' . $event->start_at->translatedFormatTime())
            ->when(
                $event->location,
                fn (MailMessage $message) => $message->line('Místo: ' . $event->location)
            )
            ->action('Zobrazit událost', route('events.show', $event));
    }
}
