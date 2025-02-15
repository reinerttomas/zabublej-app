<?php

declare(strict_types=1);

namespace App\Livewire;

enum Modal: string
{
    case UserUpdate = 'user-update';
    case UserDelete = 'user-delete';
}
