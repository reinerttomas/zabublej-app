<?php

declare(strict_types=1);

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    //
}; ?>

<div>
    <flux:heading size="xl">{{ __('Good afternoon, Olivia') }}</flux:heading>
    <flux:subheading size="lg">{{ __('Short description of page') }}</flux:subheading>

    <flux:separator class="my-6" variant="subtle" />
</div>
