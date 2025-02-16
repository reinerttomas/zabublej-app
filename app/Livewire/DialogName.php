<?php

declare(strict_types=1);

namespace App\Livewire;

enum DialogName: string
{
    case UserUpdate = 'user-update';
    case UserDelete = 'user-delete';
    case EventCreate = 'event-create';
    case EventDelete = 'event-delete';
}
