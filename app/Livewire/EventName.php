<?php

declare(strict_types=1);

namespace App\Livewire;

enum EventName: string
{
    case ProfileInformationUpdated = 'profile-information-updated';
}
