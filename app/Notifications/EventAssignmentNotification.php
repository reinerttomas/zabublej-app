<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

final class EventAssignmentNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly Event $event,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(User $user): MailMessage
    {
        return (new MailMessage)
            ->subject('Přiřazení k události')
            ->greeting('Ahoj, ' . $user->name)
            ->line('Byli jste přiřazeni k následující události:')
            ->line('Název události: ' . $this->event->name)
            ->when(
                $this->event->location,
                fn (MailMessage $message) => $message->line('Místo konání: ' . $this->event->location)
            )
            ->line('Datum a čas: ' . $this->event->start_at->format('d.m.Y H:i'))
            ->action('Zobrazit detail události', url('/events/' . $this->event->id))
            ->line('Díky za spolupráci!');
    }
}
