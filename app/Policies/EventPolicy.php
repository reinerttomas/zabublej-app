<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final readonly class EventPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(Permission::ViewAnyEvent, Permission::ViewEvent);
    }

    public function viewAll(User $user): bool
    {
        return $user->hasPermissionTo(Permission::ViewAnyEvent);
    }

    public function viewPrice(User $user): bool
    {
        return $user->hasPermissionTo(Permission::ViewEventPrice);
    }

    public function view(User $user, Event $event): bool
    {
        if ($user->hasPermissionTo(Permission::ViewAnyEvent)) {
            return true;
        }

        if ($user->hasPermissionTo(Permission::ViewEvent)) {
            return $user->events()->whereKey($event->id)->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CreateEvent);
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo(Permission::UpdateEvent);
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(Permission::DeleteEvent);
    }
}
