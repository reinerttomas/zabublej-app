<?php

declare(strict_types=1);

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    //
}; ?>

<div>
    <flux:heading size="xl">{{ __('Events') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('Short description of page') }}</flux:subheading>

    <flux:separator variant="subtle" />
</div>
