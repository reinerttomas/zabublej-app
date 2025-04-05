<?php

declare(strict_types=1);

namespace App\Builders;

use App\Enums\Database\Direction;
use App\Exceptions\InvalidArgumentException;
use Closure;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @extends \Illuminate\Database\Eloquent\Builder<TModel>
 */
abstract class Builder extends EloquentBuilder
{
    public function orConditions(Closure ...$conditions): static
    {
        if ($conditions === []) {
            throw new InvalidArgumentException('At least one condition must be provided.');
        }

        return $this->where(function (self $query) use ($conditions): void {
            $conditionsCollection = collect($conditions);

            // Aplikujeme první podmínku
            $conditionsCollection->first()($query);

            // Aplikujeme všechny ostatní podmínky s OR
            $conditionsCollection->skip(1)->each(function (Closure $condition) use ($query): void {
                $query->orWhere(function (self $subQuery) use ($condition): void {
                    $condition($subQuery);
                });
            });
        });
    }

    public function orderByDirection(string $sortBy, Direction $direction = Direction::ASC): static
    {
        $this->orderBy($sortBy, $direction->value);

        return $this;
    }
}
