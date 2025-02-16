<?php

declare(strict_types=1);

use App\Livewire\WithPagination;
use App\Livewire\WithSearching;
use App\Livewire\WithSorting;
use App\Models\Event;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {}; ?>

<div class="space-y-6">
    <flux:heading size="xl">{{ __('Events') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('Short description of page') }}</flux:subheading>

    <flux:separator variant="subtle" />

    <livewire:events.table />
</div>
