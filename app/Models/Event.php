<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\EventBuilder;
use App\Contracts\EventState;
use App\Enums\EventStatus;
use App\Observers\EventObserver;
use App\States\Events\EventCancelledState;
use App\States\Events\EventCompletedState;
use App\States\Events\EventDraftState;
use App\States\Events\EventPublishedState;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Carbon\CarbonImmutable $start_at
 * @property string|null $location
 * @property string|null $contact_person
 * @property string|null $contact_email
 * @property string|null $contact_phone
 * @property bool $is_multi_person
 * @property int|null $children_count
 * @property int|null $workers_count
 * @property int|null $price
 * @property int|null $reward
 * @property string|null $note
 * @property EventStatus $status
 * @property \Carbon\CarbonImmutable|null $published_at
 * @property \Carbon\CarbonImmutable|null $cancelled_at
 * @property \Carbon\CarbonImmutable|null $completed_at
 * @property \Carbon\CarbonImmutable $created_at
 * @property \Carbon\CarbonImmutable $updated_at
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
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps();
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
