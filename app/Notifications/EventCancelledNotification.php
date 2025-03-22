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

final class EventCancelledNotification extends Notification implements ShouldQueue
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
            ->subject('Událost byla zrušena')
            ->greeting('Ahoj, ' . $user->name)
            ->line('Událost, ke které jste byl(a) přiřazen(a), byla zrušena.')
            ->line('Název události: ' . $this->event->name)
            ->when(
                $this->event->location,
                fn (MailMessage $message) => $message->line('Místo konání: ' . $this->event->location)
            )
            ->line('Původní datum a čas: ' . $this->event->start_at->format('d.m.Y H:i'))
            ->line('V případě dotazů kontaktujte organizátora.')
            ->line('Díky za pochopení.');
    }
}
