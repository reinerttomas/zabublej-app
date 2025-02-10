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
        <flux:heading size="xl">{{ __('Profile') }}</flux:heading>
        <flux:subheading size="lg">{{ __("Here's what's new today") }}</flux:subheading>
    </div>

    <flux:separator variant="subtle" />

    <div class="space-y-6">
        <flux:card>
            <div class="max-w-xl">
                <livewire:profile.update-profile-information-form />
            </div>
        </flux:card>

        <flux:card>
            <div class="max-w-xl">
                <livewire:profile.update-password-form />
            </div>
        </flux:card>

        <flux:card>
            <div class="max-w-xl">
                <livewire:profile.delete-user-form />
            </div>
        </flux:card>
    </div>
</div>
