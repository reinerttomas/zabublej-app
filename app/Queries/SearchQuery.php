<?php

declare(strict_types=1);

namespace App\Queries;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

final readonly class SearchQuery
{
    public function __construct(
        private string $search,
        private Closure $callback,
    ) {}

    public function __invoke(Builder $builder): void
    {
        $value = Str::of($this->search);

        if ($value->isEmpty()) {
            return;
        }

        ($this->callback)($builder);
    }
}
