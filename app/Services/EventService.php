<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\EventStatus;
use App\Models\Event;

final readonly class EventService
{
    /**
     * @return array<string, mixed>
     */
    public function prepareAttributesForStatusChange(EventStatus $status, ?Event $event): array
    {
        $attributes = match ($status) {
            EventStatus::Draft => [
                'published_at' => null,
                'cancelled_at' => null,
                'completed_at' => null,
            ],

            EventStatus::Published => [
                'published_at' => $event->published_at ?? now(),
                'cancelled_at' => null,
                'completed_at' => null,
            ],

            EventStatus::Cancelled => [
                'published_at' => $event->published_at,
                'cancelled_at' => $event->cancelled_at ?? now(),
                'completed_at' => null,
            ],

            EventStatus::Completed => [
                'published_at' => $event->published_at ?? now(),
                'cancelled_at' => null,
                'completed_at' => $event->completed_at ?? now(),
            ],
        };

        return [
            'status' => $status,
            ...$attributes,
        ];
    }
}
