<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\EventAttendanceBuilder;
use App\Enums\EventAttendanceStatus;
use App\Observers\EventAttendanceObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read int $event_id
 * @property-read int $user_id
 * @property EventAttendanceStatus $status
 * @property-read int|null $processor_id
 * @property-read \Carbon\CarbonImmutable|null $processed_at
 * @property-read \Carbon\CarbonImmutable|null $created_at
 * @property-read \Carbon\CarbonImmutable|null $updated_at
 */
#[ObservedBy(EventAttendanceObserver::class)]
final class EventAttendance extends Model
{
    public function newEloquentBuilder($query): EventAttendanceBuilder
    {
        return new EventAttendanceBuilder($query);
    }

    /**
     * @return BelongsTo<Event, $this>
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processor');
    }

    public function setStatus(EventAttendanceStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'status' => EventAttendanceStatus::class,
            'processed_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
