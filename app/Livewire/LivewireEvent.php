<?php

declare(strict_types=1);

namespace App\Livewire;

enum LivewireEvent: string
{
    case ToastShow = 'toast-show';

    case ProfileInformationUpdated = 'profile-information-updated';
}
