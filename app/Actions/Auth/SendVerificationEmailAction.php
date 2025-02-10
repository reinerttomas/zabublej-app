<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;

final readonly class SendVerificationEmailAction
{
    public function execute(User $user): void
    {
        $user->sendEmailVerificationNotification();
    }
}
