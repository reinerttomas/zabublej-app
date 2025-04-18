<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\UserBuilder;
use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $email
 * @property-read \Carbon\CarbonImmutable|null $email_verified_at
 * @property-read string|null $phone
 * @property-read string $password
 * @property-read \Carbon\CarbonImmutable|null $last_login_at
 * @property-read \Carbon\CarbonImmutable $created_at
 * @property-read \Carbon\CarbonImmutable $updated_at
 * @property-read \Carbon\CarbonImmutable|null $deleted_at
 */
#[ObservedBy(UserObserver::class)]
final class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function newEloquentBuilder($query): UserBuilder
    {
        return new UserBuilder($query);
    }

    /**
     * @return BelongsToMany<Event, $this>
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class)
            ->withTimestamps();
    }

    /**
     * @return HasMany<EventAttendance, $this>
     */
    public function eventAttendances(): HasMany
    {
        return $this->hasMany(EventAttendance::class);
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
