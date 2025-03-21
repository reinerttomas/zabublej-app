<?php

declare(strict_types=1);

use App\Enums\EventStatus;
use App\Enums\Livewire\LivewireEvent;
use App\Enums\Permission;
use App\Exceptions\NotImplementedException;
use App\Models\Event;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component
{
    public Event $event;

    public function mount(): void
    {
        Gate::authorize('update', $this->event);
    }

    #[On(LivewireEvent::EventUpdated->value)]
    public function refresh(): void
    {
        $this->event->refresh();
    }

    public function changeStatus(EventStatus $status): void
    {
        Gate::authorize('update', $this->event);

        match ($status) {
            EventStatus::Published => $this->event->state()->published(),
            EventStatus::Cancelled => $this->event->state()->cancelled(),
            EventStatus::Completed => $this->event->state()->completed(),
            default => throw new NotImplementedException(),
        };

        $this->event->save();

        $this->dispatch(LivewireEvent::EventUpdated);

        Flux::toast('Saved.', variant: 'success');
    }
}; ?>

<section class="w-full">
    <div class="relative mb-6 w-full">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">{{ $event->name }}</flux:heading>
                <flux:subheading size="lg" class="mb-6">{{ __('Manage your events and attendees') }}</flux:subheading>
            </div>
            <div class="flex items-end gap-4">
                @can('update', $event)
                    @switch($event->status)
                        @case(EventStatus::Draft)
                            <flux:button variant="primary" wire:click="changeStatus({{ EventStatus::Published }})">
                                {{ __('Zveřejnit') }}
                            </flux:button>
                            <flux:button variant="danger" wire:click="changeStatus({{ EventStatus::Cancelled }})">
                                {{ __('Zrušit') }}
                            </flux:button>

                            @break
                        @case(EventStatus::Published)
                            <flux:button variant="primary" wire:click="changeStatus({{ EventStatus::Completed }})">
                                {{ __('Dokončit') }}
                            </flux:button>
                            <flux:button variant="danger" wire:click="changeStatus({{ EventStatus::Cancelled }})">
                                {{ __('Zrušit') }}
                            </flux:button>

                            @break
                    @endswitch
                @endcan
            </div>
        </div>
        <flux:separator variant="subtle" />
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <flux:card>
                <livewire:events.update-event-form :event="$event" />
            </flux:card>
        </div>
        <div class="space-y-6">
            <flux:card>
                <livewire:events.update-event-user-form :event="$event" />
            </flux:card>
        </div>
    </div>
</section>
