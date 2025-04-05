<?php

declare(strict_types=1);

namespace App\States\Events;

final class EventUserPendingState extends BaseEventUserState
{
    public function approved(): void
    {
        $this->eventUser->setStatus(new EventUserApprovedState($this->eventUser));
    }

    public function rejected(): void
    {
        $this->eventUser->setStatus(new EventUserRejectedState($this->eventUser));
    }
}
