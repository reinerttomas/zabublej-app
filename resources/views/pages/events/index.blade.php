<?php

declare(strict_types=1);

use App\Actions\Events\CreateEventAction;
use App\Livewire\DialogName;
use App\Livewire\WithPagination;
use App\Livewire\WithSearching;
use App\Livewire\WithSorting;
use App\Models\Event;
use Carbon\CarbonImmutable;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public function showDialogCreate(): void
    {
        $this->modal(DialogName::EventCreate)->show();
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Events') }}</flux:heading>
            <flux:subheading size="lg">{{ __('Short description of page') }}</flux:subheading>
        </div>
        <div>
            <flux:button wire:click="showDialogCreate" variant="primary">{{ __('Create Event') }}</flux:button>
        </div>
    </div>

    <flux:separator variant="subtle" />

    <livewire:events.table />

    <flux:modal name="{{ DialogName::EventCreate }}" class="w-full">
        <livewire:events.create-event-form />
    </flux:modal>
</div>
