<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\InvitationBuilder;
use App\ValueObjects\InvitationPayload;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * @property string $id
 * @property string $email
 * @property InvitationPayload $payload
 * @property CarbonImmutable $expires_at
 * @property CarbonImmutable|null $accepted_at
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 */
final class Invitation extends Model
{
    /** @use HasFactory<\Database\Factories\InvitationFactory> */
    use HasFactory, HasUuids, Notifiable;

    public function newEloquentBuilder($query): InvitationBuilder
    {
        return new InvitationBuilder($query);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isAccepted(): bool
    {
        return $this->accepted_at !== null;
    }

    protected function casts(): array
    {
        return [
            'payload' => InvitationPayload::class,
            'expires_at' => 'datetime',
            'accepted_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
