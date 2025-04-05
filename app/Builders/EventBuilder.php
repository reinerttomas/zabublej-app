<?php

declare(strict_types=1);

namespace App\Builders;

use App\Enums\EventStatus;
use Closure;

/**
 * @extends Builder<\App\Models\Event>
 */
final class EventBuilder extends Builder
{
    public function search(string $value): void
    {
        $value = "%$value%";

        $this->where('name', 'like', $value)
            ->orWhere('location', 'like', $value);
    }

    public function whereHasEventAttendances(?Closure $condition = null): self
    {
        return $this->whereHas('eventAttendances', $condition);
    }

    public function whereDoesntHaveEventAttendances(?Closure $condition = null): self
    {
        return $this->whereDoesntHave('eventAttendances', $condition);
    }

    public function whereStatus(EventStatus ...$status): self
    {
        is_array($status)
            ? $this->whereIn('status', $status)
            : $this->where('status', $status);

        return $this;
    }

    public function whereNotStatus(EventStatus ...$status): self
    {
        is_array($status)
            ? $this->whereNotIn('status', $status)
            : $this->whereNot('status', $status);

        return $this;
    }

    public function withPendingWorkersCount(): self
    {
        return $this->withCount([
            'eventAttendances as pending_users_count' => function (EventAttendanceBuilder $query): void {
                $query->pending();
            },
        ]);
    }

    public function withApprovedWorkersCount(): self
    {
        return $this->withCount([
            'eventAttendances as approved_users_count' => function (EventAttendanceBuilder $query): void {
                $query->approved();
            },
        ]);
    }
}
