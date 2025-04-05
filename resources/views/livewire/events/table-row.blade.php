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
    <flux:table.cell>{{ $event->start_at?->translatedFormatDate() }}</flux:table.cell>
    <flux:table.cell>{{ $event->start_at?->translatedFormatTime() }}</flux:table.cell>
    <flux:table.cell>{{ $event->formattedReward() }}</flux:table.cell>
    <flux:table.cell></flux:table.cell>
    <flux:table.cell>
        <flux:avatar.group>
            @foreach ($event->confirmedUsers as $user)
                <flux:avatar tooltip="{{ $user->name }}" name="{{ $user->name }}" size="sm" circle />
            @endforeach
        </flux:avatar.group>
    </flux:table.cell>
    <flux:table.cell>
        <flux:badge color="{{ $event->status->badge() }}" size="sm">
            {{ $event->status->label() }}
        </flux:badge>
    </flux:table.cell>

    @canany(['update', 'delete'], $event)
        <flux:table.cell>
            <flux:dropdown>
                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>

                <flux:menu>
                    @can('update', $event)
                        <flux:menu.item
                            class="justify-between"
                            href="{{ route('events.edit', $event) }}"
                            wire:navigate
                        >
                            <div>{{ __('Upravit') }}</div>
                            <flux:icon.square-pen variant="micro" />
                        </flux:menu.item>
                    @endcan

                    <flux:menu.separator />

                    @can('delete', $event)
                        <flux:menu.item class="justify-between" variant="danger" wire:click="showDialogDelete">
                            <div>{{ __('Smazat') }}</div>
                            <flux:icon.trash-2 variant="micro" />
                        </flux:menu.item>
                    @endcan
                </flux:menu>
            </flux:dropdown>

            @can('delete', $event)
                <flux:modal name="{{ DialogName::EventDelete }}" class="w-full max-w-lg">
                    <form class="space-y-6" wire:submit="$parent.delete({{ $event->id }})">
                        <div>
                            <flux:heading size="lg">
                                {{ __('Opravdu chcete odstranit tuto událost?') }}
                            </flux:heading>
                        </div>

                        <div class="space-y-2">
                            <flux:text variant="strong" class="flex items-center gap-2">
                                {{ $event->name }}
                            </flux:text>
                            <flux:text class="flex items-center gap-2">
                                <flux:icon.calendar-days variant="micro" />
                                {{ $event->start_at->translatedFormatDate() }}
                            </flux:text>
                            <flux:text class="flex items-center gap-2">
                                <flux:icon.clock variant="micro" />
                                {{ $event->start_at->translatedFormatTime() }}
                            </flux:text>
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
