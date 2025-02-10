<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

final readonly class LogoutAction
{
    /**
     * Log the current user out of the application.
     */
    public function execute(): void
    {
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();
    }
}
