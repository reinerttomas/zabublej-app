<?php

declare(strict_types=1);

namespace App\Actions\Auth;

final readonly class ConfirmPasswordAction
{
    public function execute(): void
    {
        session(['auth.password_confirmed_at' => time()]);
    }
}
