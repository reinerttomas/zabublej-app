<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Attributes\Url;

trait WithSearching
{
    #[Url(except: '')]
    public string $search = '';
}
