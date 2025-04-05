<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\EventBuilder;
use App\Contracts\Events\EventState;
use App\Enums\EventAttendanceStatus;
use App\Enums\EventStatus;
use App\Observers\EventObserver;
use App\States\Events\EventCancelledState;
use App\States\Events\EventCompletedState;
use App\States\Events\EventDraftState;
use App\States\Events\EventPublishedState;
use App\ValueObjects\EventCapacity;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Number;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string|null $description
 * @property-read \Carbon\CarbonImmutable $start_at
 * @property-read string|null $location
 * @property-read string|null $contact_person
 * @property-read string|null $contact_email
 * @property-read string|null $contact_phone
 * @property-read bool $is_multi_person
 * @property-read int|null $estimated_children_count
 * @property-read int|null $max_workers
 * @property-read int|null $price
 * @property-read int|null $reward
 * @property-read string|null $note
 * @property EventStatus $status
 * @property-read \Carbon\CarbonImmutable|null $published_at
 * @property-read \Carbon\CarbonImmutable|null $cancelled_at
 * @property-read \Carbon\CarbonImmutable|null $completed_at
 * @property-read \Carbon\CarbonImmutable $created_at
 * @property-read \Carbon\CarbonImmutable $updated_at
 * @property-read int $pending_users_count
 * @property-read int $confirmed_users_count
 */
#[ObservedBy(EventObserver::class)]
final class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory, SoftDeletes;

    protected $attributes = [
        'status' => EventStatus::Draft,
    ];

    public function newEloquentBuilder($query): EventBuilder
    {
        return new EventBuilder($query);
    }

    /**
     * @return BelongsToMany<User, EventAttendance>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, EventAttendance::class);
    }

    public function pendingUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, EventAttendance::class)
            ->wherePivot('status', EventAttendanceStatus::Pending);
    }

    public function confirmedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, EventAttendance::class)
            ->wherePivot('status', EventAttendanceStatus::Confirmed);
    }

    public function eventAttendances(): HasMany
    {
        return $this->hasMany(EventAttendance::class);
    }

    public function state(): EventState
    {
        return match ($this->status) {
            EventStatus::Draft => new EventDraftState($this),
            EventStatus::Published => new EventPublishedState($this),
            EventStatus::Completed => new EventCompletedState($this),
            EventStatus::Cancelled => new EventCancelledState($this),
        };
    }

    public function setStatus(EventStatus $status): void
    {
        $this->status = $status;
    }

    public function formattedPrice(): string
    {
        return Number::currency($this->price ?? 'xxx', 'CZK', locale: 'cs_CZ', precision: 0);
    }

    public function formattedReward(): string
    {
        return Number::currency($this->reward ?? 'xxx', 'CZK', locale: 'cs_CZ', precision: 0);
    }

    public function getCapacity(): EventCapacity
    {
        return new EventCapacity(
            $this->max_workers,
            $this->pending_users_count ?? $this->pendingUsers()->count(),
            $this->confirmed_users_count ?? $this->confirmedUsers()->count(),
        );
    }

    /**
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'is_multi_person' => 'boolean',
            'status' => EventStatus::class,
            'published_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }
}
