<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Models\EventAttendance;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final readonly class EventAttendancePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permission::ViewAnyEventAttendance);
    }

    public function update(User $user, EventAttendance $eventAttendance): bool
    {
        return $user->hasPermissionTo(Permission::UpdateEventAttendance);
    }
}
