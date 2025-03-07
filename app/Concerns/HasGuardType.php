<?php

declare(strict_types=1);

namespace App\Concerns;

use Error;

use function get_debug_type;
use function sprintf;

trait HasGuardType
{
    final public static function guardType(mixed $model): static
    {
        return $model instanceof static ? $model
            : throw new Error(sprintf(
                'Expected instance of model "%s", got instance of "%s',
                static::class,
                get_debug_type($model))
            );
    }
}
