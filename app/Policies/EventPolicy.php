<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\EventAttendanceStatus;
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

    public function view(User $user): bool
    {
        if ($user->hasPermissionTo(Permission::ViewAnyEvent)) {
            return true;
        }

        if ($user->hasPermissionTo(Permission::ViewEvent)) {
            return $user->eventAttendances()
                ->whereUserId($user->id)
                ->whereStatus(EventAttendanceStatus::Confirmed)
                ->exists();
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

    public function addWorker(User $user, Event $event): bool
    {
        return $user->hasPermissionTo(Permission::UpdateEvent)
            && $event->start_at->isFuture()
            && ! $event->status->isCancelled()
            && ! $event->status->isCompleted()
            && $event->getCapacity()->hasFreeCapacity();
    }

    public function removeWorker(User $user, Event $event): bool
    {
        return $user->hasPermissionTo(Permission::UpdateEvent)
            && $event->start_at->isFuture()
            && ! $event->status->isCancelled()
            && ! $event->status->isCompleted();
    }

    public function signIn(User $user, Event $event): bool
    {
        return $event->eventAttendances()
            ->whereUserId($user->id)
            ->doesntExist();
    }
}
