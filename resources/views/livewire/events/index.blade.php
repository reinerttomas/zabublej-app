<?php

declare(strict_types=1);

use App\Enums\Livewire\DialogName;
use App\Enums\Permission;
use App\Models\Event;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new class extends Component
{
    public function mount(): void
    {
        Gate::authorize('viewAny', Event::class);
    }

    public function showDialogCreate(): void
    {
        $this->modal(DialogName::EventCreate)->show();
    }
}; ?>

<section class="w-full">
    <div class="relative mb-6 w-full">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">{{ __('Události') }}</flux:heading>
                <flux:subheading size="lg" class="mb-6">{{ __('Správa událostí a bublinářů') }}</flux:subheading>
            </div>
            <div>
                @can('create', Event::class)
                    <flux:button variant="primary" wire:click="showDialogCreate">
                        {{ __('Vytvořit událost') }}
                    </flux:button>
                @endcan
            </div>
        </div>
        <flux:separator variant="subtle" />
    </div>

    <livewire:events.table />

    @can('create', Event::class)
        <flux:modal name="{{ DialogName::EventCreate }}" class="w-full max-w-lg">
            <livewire:events.create-event-form />
        </flux:modal>
    @endcan
</section>
