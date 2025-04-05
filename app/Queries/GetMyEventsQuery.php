<?php

declare(strict_types=1);

namespace App\Queries;

use App\Builders\EventAttendanceBuilder;
use App\Builders\EventBuilder;
use App\Support\Facades\Auth;

final readonly class GetMyEventsQuery
{
    public function __invoke(EventBuilder $query): void
    {
        $query
            ->whereHasEventAttendances(function (EventAttendanceBuilder $query): void {
                $query->whereUserId(Auth::userOrFail()->id);
            })
            ->where('events.start_at', '>=', now())
            ->orderBy('events.start_at');
    }
}
