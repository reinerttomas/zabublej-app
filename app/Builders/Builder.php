<?php

declare(strict_types=1);

namespace App\Builders;

use App\Enums\Database\SortDirection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @extends \Illuminate\Database\Eloquent\Builder<TModel>
 */
abstract class Builder extends EloquentBuilder
{
    final public function orderByDirection(string $sortBy, SortDirection $direction = SortDirection::ASC): self
    {
        return $this->orderBy($sortBy, $direction->value);
    }
}
