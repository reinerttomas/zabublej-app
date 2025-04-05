<?php

declare(strict_types=1);

namespace App\Queries;

use App\Builders\EventAttendanceBuilder;
use App\Builders\EventBuilder;
use App\Enums\EventAttendanceStatus;
use App\Enums\EventStatus;
use App\Support\Facades\Auth;
use Illuminate\Database\Query\Builder as QueryBuilder;

final readonly class GetAvailableEventsQuery
{
    public function __invoke(EventBuilder $query): void
    {
        $query
            ->where('events.status', EventStatus::Published)
            ->where('events.start_at', '>', now())
            ->whereDoesntHaveEventAttendances(function (EventAttendanceBuilder $query): void {
                $query->whereUserId(Auth::userOrFail()->id);
            })
            ->where('events.max_workers', '>', function (QueryBuilder $query): void {
                $query->selectRaw('COUNT(*)')
                    ->from('event_attendances')
                    ->whereColumn('event_attendances.event_id', 'events.id')
                    ->whereIn('event_attendances.status', [
                        EventAttendanceStatus::Pending,
                        EventAttendanceStatus::Confirmed,
                    ]);
            })
            ->orderBy('events.start_at');
    }
}
