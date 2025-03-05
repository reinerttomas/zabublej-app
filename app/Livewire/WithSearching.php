<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Support\Str;
use Livewire\Attributes\Url;

trait WithSearching
{
    #[Url(except: '')]
    public string $search = '';

    public function isSearchSet(): bool
    {
        return Str::of($this->search)->trim()->isNotEmpty();
    }
}
