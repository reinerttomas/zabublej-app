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

#[ObservedBy(EventObserver::class)]
final class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory, SoftDeletes;

    protected $attributes = [
        'status' => EventStatus::Draft,
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'is_multi_person' => 'boolean',
        'status' => EventStatus::class,
        'published_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
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
}
