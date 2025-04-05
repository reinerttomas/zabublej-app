<?php

declare(strict_types=1);

namespace App\Builders;

use App\Enums\EventAttendanceStatus;

/**
 * @extends Builder<\App\Models\EventAttendance>
 */
final class EventAttendanceBuilder extends Builder
{
    public function whereEventId(int $eventId): self
    {
        return $this->where('event_id', $eventId);
    }

    public function whereUserId(int $userId): self
    {
        return $this->where('user_id', $userId);
    }

    public function whereStatus(EventAttendanceStatus $status): self
    {
        return $this->where('status', $status);
    }

    public function whereNotStatus(EventAttendanceStatus ...$status): self
    {
        is_array($status)
            ? $this->whereNotIn('status', $status)
            : $this->whereIn('status', $status);

        return $this;
    }

    public function pending(): self
    {
        return $this->whereStatus(EventAttendanceStatus::Pending);
    }

    public function approved(): self
    {
        return $this->whereStatus(EventAttendanceStatus::Confirmed);
    }

    public function rejected(): self
    {
        return $this->whereStatus(EventAttendanceStatus::Rejected);
    }
}
