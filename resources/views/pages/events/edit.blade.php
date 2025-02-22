<?php

declare(strict_types=1);

use App\Models\Event;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public Event $event;
}; ?>

<div class="space-y-6">
    <div>
        <flux:heading size="xl">{{ $event->name }}</flux:heading>
        <flux:breadcrumbs class="mt-4">
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('events.index') }}">Events</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <flux:card>
                <livewire:events.update-event-form :event="$event" />
            </flux:card>
        </div>
        <div class="space-y-6">
            <flux:card>
                <livewire:events.update-status-form :event="$event" />
            </flux:card>

            <flux:card>
                <livewire:events.update-event-user-form :event="$event" />
            </flux:card>
        </div>
    </div>
</div>
