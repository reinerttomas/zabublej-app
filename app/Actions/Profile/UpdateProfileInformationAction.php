<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Models\User;

final readonly class UpdateProfileInformationAction
{
    public function execute(User $user, array $data): void
    {
        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
    }
}
