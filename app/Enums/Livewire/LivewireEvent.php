<?php

declare(strict_types=1);

namespace App\Enums\Livewire;

enum LivewireEvent: string
{
    case ProfileInformationUpdated = 'profile-information-updated';
    case EventUpdated = 'event-updated';
    case EventsRefresh = 'events-refresh';
    case EventAttendancesRefresh = 'event-attendances-refresh';
    case InvitationCreated = 'invitation-created';
}
