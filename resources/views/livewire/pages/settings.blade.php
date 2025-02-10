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
        <flux:heading size="xl">{{ __('Settings') }}</flux:heading>
        <flux:subheading size="lg">{{ __('This is secured area.') }}</flux:subheading>
    </div>

    <flux:separator variant="subtle" />
</div>
