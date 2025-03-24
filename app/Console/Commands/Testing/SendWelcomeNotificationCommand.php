<?php

declare(strict_types=1);

namespace App\Console\Commands\Testing;

use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Console\Command;

final class SendWelcomeNotificationCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'testing:send-welcome {email}';

    /**
     * @var string
     */
    protected $description = 'Send welcome notification to the user.';

    public function handle(): void
    {
        $email = $this->argument('email');

        $this->comment("Sending to $email");

        User::whereEmail($email)
            ->firstOrFail()
            ->notify(new WelcomeNotification);

        $this->info('Notification sent.');
    }
}
