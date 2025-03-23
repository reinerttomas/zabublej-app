<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Str;

final readonly class UserObserver
{
    public function saving(User $user): void
    {
        if ($user->isDirty('phone') && $user->phone !== null) {
            $user->setPhone(Str::remove(' ', $user->phone));
        }
    }
}
