<?php

declare(strict_types=1);

namespace App\Livewire;

trait WithPagination
{
    use \Livewire\WithPagination;

    public int $perPage = 20;
}
