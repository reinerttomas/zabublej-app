<?php

declare(strict_types=1);

use App\Enums\Livewire\DialogName;
use App\Enums\Permission;
use App\Models\Event;
use App\Policies\UserPolicy;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component
{
    public Event $event;

    public function showDialogDelete(): void
    {
        $this->modal(DialogName::EventDelete)->show();
    }
}; ?>

<flux:table.row>
    <flux:table.cell>
        <flux:link variant="subtle" href="{{ route('events.show', $event) }}" wire:navigate>
            {{ $event->name }}
        </flux:link>
    </flux:table.cell>
    <flux:table.cell>{{ $event->location }}</flux:table.cell>
    <flux:table.cell>{{ $event->children_count }}</flux:table.cell>
    <flux:table.cell>
        @foreach ($event->users as $user)
            <flux:tooltip content="{{ $user->name }}">
                <flux:badge size="sm">{{ $user->initials() }}</flux:badge>
            </flux:tooltip>
        @endforeach
    </flux:table.cell>
    <flux:table.cell>{{ $event->start_at?->formatDate() }}</flux:table.cell>
    <flux:table.cell>{{ $event->start_at?->formatTime() }}</flux:table.cell>
    <flux:table.cell>
        <flux:badge color="{{ $event->status->badge() }}" size="sm">
            {{ $event->status->label() }}
        </flux:badge>
    </flux:table.cell>

    @canany([Permission::UpdateEvent, Permission::DeleteEvent])
        <flux:table.cell>
            <flux:dropdown>
                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>

                <flux:menu>
                    @can(Permission::UpdateEvent)
                        <flux:menu.item
                            class="justify-between"
                            href="{{ route('events.edit', $event) }}"
                            wire:navigate
                        >
                            <div>{{ __('Edit') }}</div>
                            <flux:icon.square-pen variant="micro" />
                        </flux:menu.item>
                    @endcan

                    <flux:menu.separator />

                    @can(Permission::DeleteEvent)
                        <flux:menu.item class="justify-between" variant="danger" wire:click="showDialogDelete">
                            <div>{{ __('Delete') }}</div>
                            <flux:icon.trash-2 variant="micro" />
                        </flux:menu.item>
                    @endcan
                </flux:menu>
            </flux:dropdown>

            @can(Permission::DeleteEvent)
                <flux:modal name="{{ DialogName::EventDelete }}" class="min-w-[22rem]">
                    <form class="space-y-6" wire:submit="$parent.delete({{ $event->id }})">
                        <div>
                            <flux:heading size="lg">{{ __('Delete event') }}</flux:heading>

                            <flux:subheading>
                                <p>
                                    {{ __('Are you sure you want to delete') }}
                                    <strong>{{ $event->name }}</strong>
                                </p>
                            </flux:subheading>
                        </div>

                        <div class="flex gap-2">
                            <flux:spacer />

                            <flux:modal.close>
                                <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                            </flux:modal.close>

                            <flux:button type="submit" variant="danger">{{ __('Delete') }}</flux:button>
                        </div>
                    </form>
                </flux:modal>
            @endcan
        </flux:table.cell>
    @endcan
</flux:table.row>
