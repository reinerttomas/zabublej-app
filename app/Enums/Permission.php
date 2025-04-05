<?php

declare(strict_types=1);

namespace App\Enums;

enum Permission: string
{
    // Users
    case ViewAnyUser = 'view-any-user';
    case ViewUser = 'view-user';
    case InviteUser = 'invite-user';
    case UpdateUser = 'update-user';
    case DeleteUser = 'delete-user';

    // Events
    case ViewAnyEvent = 'view-any-event';
    case ViewEvent = 'view-event';
    case CreateEvent = 'create-event';
    case UpdateEvent = 'update-event';
    case DeleteEvent = 'delete-event';

    // Event Users
    case ViewAnyEventAttendance = 'view-any-event-attendance';
    case UpdateEventAttendance = 'update-event-attendance';
}
