<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Attributes\Url;

trait WithPagination
{
    use \Livewire\WithPagination;

    #[Url]
    public int $perPage = 20;
}
