<?php

declare(strict_types=1);

use App\Builders\EventBuilder;
use App\Livewire\WithPagination;
use App\Livewire\WithSearching;
use App\Livewire\WithSorting;
use App\Models\Event;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    use WithPagination;
    use WithSearching;
    use WithSorting;

    public Event $event;

    #[Computed]
    public function events(): LengthAwarePaginator
    {
        return Event::query()
            ->when($this->canSearching(), function (EventBuilder $query): void {
                $query->search($this->search);
            })
            ->orderByDirection($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function delete(int $id): void
    {
        Event::findOrFail($id)->delete();
    }
}; ?>

<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:justify-between">
        <flux:input
            class="md:max-w-sm"
            icon="magnifying-glass"
            placeholder="{{ __('Search ...') }}"
            wire:model.live.debounce.300ms="search"
            clearable
        />

        <x-per-page :per-page="$perPage" />
    </div>

    <flux:table :paginate="$this->events">
        <flux:columns>
            <flux:column
                sortable
                :sorted="$sortBy === 'name'"
                :direction="$sortDirection->value"
                wire:click="sort('name')"
            >
                {{ __('Name') }}
            </flux:column>
            <flux:column>{{ __('Location') }}</flux:column>
            <flux:column
                sortable
                :sorted="$sortBy === 'start_at'"
                :direction="$sortDirection->value"
                wire:click="sort('start_at')"
            >
                {{ __('Start at') }}
            </flux:column>
            <flux:column>{{ __('Estimated') }}</flux:column>
            <flux:column>{{ __('Status') }}</flux:column>
        </flux:columns>

        <flux:rows>
            @foreach ($this->events as $event)
                <livewire:events.table-row :event="$event" :key="$event->id" />
            @endforeach
        </flux:rows>
    </flux:table>
</div>
