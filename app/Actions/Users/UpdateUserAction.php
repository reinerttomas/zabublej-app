<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Models\User;

final readonly class UpdateUserAction
{
    public function execute(User $user, array $data): void
    {
        $user->fill($data);
        $user->save();
    }
}
