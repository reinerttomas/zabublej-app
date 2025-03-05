<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\Database\Direction;

trait WithSorting
{
    public string $sortBy = 'id';
    public Direction $sortDirection = Direction::DESC;

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection->invert();
        } else {
            $this->sortBy = $column;
            $this->sortDirection = Direction::ASC;
        }
    }
}
