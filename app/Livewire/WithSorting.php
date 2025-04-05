<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\Database\Direction;

trait WithSorting
{
    public string $sortBy;
    public Direction $sortDirection;

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection->invert();
        } else {
            $this->sortBy = $column;
            $this->sortDirection = Direction::ASC;
        }
    }

    public function defaultSortBy(string $sortBy): void
    {
        if (! isset($this->sortBy)) {
            $this->sortBy = $sortBy;
        }
    }

    public function defaultSortDirection(Direction $sortDirection): void
    {
        if (! isset($this->sortDirection)) {
            $this->sortDirection = $sortDirection;
        }
    }
}
