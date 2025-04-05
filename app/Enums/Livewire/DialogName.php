<?php

declare(strict_types=1);

namespace App\Enums\Livewire;

enum DialogName: string
{
    case UserInvite = 'user-invite';
    case UserUpdate = 'user-update';
    case UserDelete = 'user-delete';
    case EventCreate = 'event-create';
    case EventUpdate = 'event-update';
    case EventDelete = 'event-delete';
    case EventRegister = 'event-register';
    case EventAttendanceCreate = 'event-attendance-create';
    case EventAttendanceReject = 'event-attendance-reject';
}
