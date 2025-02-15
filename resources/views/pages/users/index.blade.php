<?php

declare(strict_types=1);

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    //
}; ?>

<div class="space-y-6">
    <div>
        <flux:heading size="xl">{{ __('Users') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Short description of page') }}</flux:subheading>
    </div>

    <flux:separator variant="subtle" />

    <livewire:users.table />
</div>
