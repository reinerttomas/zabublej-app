<?php

declare(strict_types=1);

namespace App\ValueObjects;

use App\Enums\Role;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class InvitationPayload extends Data
{
    public function __construct(
        public string $name,
        public Role $role,
        public ?string $description,
    ) {}
}
