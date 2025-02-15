<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\Database\SortDirection;
use Illuminate\Database\Eloquent\Builder;

final readonly class SortByQuery
{
    public function __construct(
        private string $sortBy,
        private SortDirection $sortDirection,
    ) {}

    public function __invoke(Builder $query): void
    {
        if ($this->sortBy === '') {
            return;
        }

        $query->orderBy($this->sortBy, $this->sortDirection->value);
    }
}
