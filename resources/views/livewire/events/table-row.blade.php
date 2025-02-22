<?php

declare(strict_types=1);

use App\Actions\Users\UpdateUserAction;
use App\Livewire\DialogName;
use App\Models\Event;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public Event $event;

    public function showDialogDelete(): void
    {
        $this->modal(DialogName::EventDelete)->show();
    }
}; ?>

<flux:table.row>
    <flux:table.cell>{{ $event->name }}</flux:table.cell>
    <flux:table.cell>{{ $event->location }}</flux:table.cell>
    <flux:table.cell>{{ $event->start_at?->toHuman() }}</flux:table.cell>
    <flux:table.cell>{{ $event->estimated_hours }}</flux:table.cell>
    <flux:table.cell>
        <flux:badge color="{{ $event->status->badge() }}" size="sm">
            {{ $event->status->label() }}
        </flux:badge>
    </flux:table.cell>
    <flux:table.cell>
        <flux:dropdown>
            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>

            <flux:menu>
                <flux:menu.item href="{{ route('events.update', $event) }}" icon="pencil" wire:navigate>
                    {{ __('Edit') }}
                </flux:menu.item>
                <flux:menu.item wire:click="showDialogDelete" icon="trash" variant="danger">
                    {{ __('Delete') }}
                </flux:menu.item>
            </flux:menu>
        </flux:dropdown>

        <flux:modal name="{{ DialogName::EventDelete }}" class="min-w-[22rem]">
            <form class="space-y-6" wire:submit="$parent.delete({{ $event->id }})">
                <div>
                    <flux:heading size="lg">{{ __('Delete event?') }}</flux:heading>

                    <flux:subheading>
                        <p>{{ __("You're about to delete this event.") }}</p>
                    </flux:subheading>
                </div>

                <div class="flex gap-2">
                    <flux:spacer />

                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </flux:modal.close>

                    <flux:button type="submit" variant="danger">{{ __('Delete event') }}</flux:button>
                </div>
            </form>
        </flux:modal>
    </flux:table.cell>
</flux:table.row>
