<?php

declare(strict_types=1);

namespace App\Notifications\Invitation;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

final class InvitationRegisterNotification extends Notification
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Invitation $invitation,
    ) {}

    /**
     * @return list<string>
     */
    public function via(Invitation $invitation): array
    {
        return ['mail'];
    }

    public function toMail(Invitation $invitation): MailMessage
    {
        $payload = $this->invitation->payload;

        return (new MailMessage)
            ->subject('Pozvánka k registraci')
            ->greeting('Dobrý den, ' . $payload->name)
            ->line('Byli jste pozváni k registraci do naší aplikace.')
            ->when(
                $payload->description,
                fn (MailMessage $message) => $message->line($payload->description)
            )
            ->line('Kliknutím na tlačítko níže si můžete vytvořit účet.')
            ->action('Registrovat se', route('register', $this->invitation))
            ->line('Tato pozvánka je platná do ' . $this->invitation->expires_at->translatedFormatDateTime())
            ->line('Pokud jste o pozvánku nežádali, můžete tento email ignorovat.');
    }
}
