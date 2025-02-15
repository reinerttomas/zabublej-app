<?php

declare(strict_types=1);

namespace App\Livewire;

enum Event: string
{
    case ProfileInformationUpdated = 'profile-information-updated';
}
