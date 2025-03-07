<?php

declare(strict_types=1);

namespace Tests\Support;

use Illuminate\Database\Eloquent\Model;

final readonly class Dataset
{
    public function __construct(
        public ?Model $model,
        public array $input,
    ) {}

    public static function make(?Model $model = null, array $input = []): self
    {
        return new self($model, $input);
    }
}
