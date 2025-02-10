<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

final readonly class SendPasswordResetLinkAction
{
    public function execute(string $email): string
    {
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            ['email' => $email]
        );

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => __($status),
            ]);
        }

        return $status;
    }
}
