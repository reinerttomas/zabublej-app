<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\Login;

final readonly class LogLastLogin
{
    public function handle(Login $event): void
    {
        $event->user->update([
            'last_login_at' => now(),
        ]);
    }
}
