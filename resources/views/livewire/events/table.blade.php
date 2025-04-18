<?php

declare(strict_types=1);

use App\Builders\EventAttendanceBuilder;
use App\Builders\EventBuilder;
use App\Builders\UserBuilder;
use App\Enums\Database\Direction;
use App\Enums\EventAttendanceStatus;
use App\Enums\EventStatus;
use App\Enums\Livewire\DialogName;
use App\Enums\Permission;
use App\Livewire\WithPagination;
use App\Livewire\WithSearching;
use App\Livewire\WithSorting;
use App\Models\Event;
use App\Models\User;
use App\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new class extends Component
{
    use WithPagination;
    use WithSearching;
    use WithSorting;

    public function boot(): void
    {
        $this->defaultSortBy('start_at');
        $this->defaultSortDirection(Direction::DESC);
    }

    /**
     * @return LengthAwarePaginator<Event>
     */
    #[Computed]
    public function events(): LengthAwarePaginator
    {
        return Event::query()
            ->with('confirmedUsers')
            ->when($this->isSearchSet(), function (EventBuilder $query): void {
                $query->search($this->search);
            })
            ->orderByDirection($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function delete(int $id): void
    {
        $event = Event::findOrFail($id);

        Gate::authorize('delete', $event);

        $event->delete();

        $this->modal(DialogName::EventDelete)->close();

        Flux::toast('Deleted.', variant: 'success');
    }
}; ?>

<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:justify-between">
        <flux:input
            class="md:max-w-sm"
            icon="magnifying-glass"
            placeholder="{{ __('Vyhledat ...') }}"
            wire:model.live.debounce.300ms="search"
            clearable
        />
    </div>

    <flux:table :paginate="$this->events" :perPage="$perPage">
        <flux:table.columns>
            <flux:table.column
                sortable
                :sorted="$sortBy === 'name'"
                :direction="$sortDirection->value"
                wire:click="sort('name')"
            >
                {{ __('Název') }}
            </flux:table.column>
            <flux:table.column
                sortable
                :sorted="$sortBy === 'start_at'"
                :direction="$sortDirection->value"
                wire:click="sort('start_at')"
            >
                {{ __('Datum') }}
            </flux:table.column>
            <flux:table.column>{{ __('Čas') }}</flux:table.column>
            <flux:table.column>{{ __('Výplata') }}</flux:table.column>
            <flux:table.column>{{ __('Typ programu') }}</flux:table.column>
            <flux:table.column>{{ __('Bublináři') }}</flux:table.column>
            <flux:table.column>{{ __('Stav') }}</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->events as $event)
                <livewire:events.table-row :event="$event" :key="$event->id" />
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
