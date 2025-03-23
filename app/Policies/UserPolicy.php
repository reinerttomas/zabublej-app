<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final readonly class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permission::ViewAnyUser);
    }

    public function invite(User $user): bool
    {
        return $user->hasPermissionTo(Permission::InviteUser);
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo(Permission::UpdateUser);
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(Permission::DeleteUser);
    }
}
