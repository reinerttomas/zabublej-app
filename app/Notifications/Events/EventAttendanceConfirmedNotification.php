<?php

declare(strict_types=1);

namespace App\Notifications\Events;

use App\Models\Event;
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
        public readonly Event $event
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
        return (new MailMessage)
            ->subject('Přihláška potvrzena')
            ->greeting('Ahoj, ' . $user->name)
            ->line('Tvoje přihláška na událost byla schválena.')
            ->line('Název: ' . $this->event->name)
            ->line('Datum: ' . $this->event->start_at->translatedFormatDate())
            ->line('Čas: ' . $this->event->start_at->translatedFormatTime())
            ->when(
                $this->event->location,
                fn (MailMessage $message) => $message->line('Místo: ' . $this->event->location)
            )
            ->action('Zobrazit událost', route('events.show', $this->event));
    }
}
