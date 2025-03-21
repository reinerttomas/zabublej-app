<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

final readonly class Dataset
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(
        public ?User $actingAs = null,
        public ?Model $model = null,
        public array $data = [],
    ) {}
}
