<?php

declare(strict_types=1);

namespace App\Enums\Livewire;

enum DialogName: string
{
    case UserUpdate = 'user-update';
    case UserDelete = 'user-delete';
    case EventCreate = 'event-create';
    case EventUpdate = 'event-update';
    case EventDelete = 'event-delete';
    case EventAddUser = 'event-add-user';
}
