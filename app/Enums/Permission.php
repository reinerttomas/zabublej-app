<?php

declare(strict_types=1);

namespace App\Enums;

enum Permission: string
{
    case ListUser = 'list-user';
    case ShowUser = 'show-user';
    case UpdateUser = 'update-user';
    case DeleteUser = 'delete-user';

    case ListEvent = 'list-event';
    case ShowEvent = 'show-event';
    case CreateEvent = 'create-event';
    case UpdateEvent = 'update-event';
    case DeleteEvent = 'delete-event';
}
