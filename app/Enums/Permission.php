<?php

declare(strict_types=1);

namespace App\Enums;

enum Permission: string
{
    case ViewAnyUser = 'view-any-user';
    case ViewUser = 'show-user';
    case UpdateUser = 'update-user';
    case DeleteUser = 'delete-user';

    case ViewAnyEvent = 'view-any-event';
    case ViewEvent = 'show-event';
    case CreateEvent = 'create-event';
    case UpdateEvent = 'update-event';
    case DeleteEvent = 'delete-event';
}
