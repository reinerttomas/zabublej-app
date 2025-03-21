<?php

declare(strict_types=1);

use App\Builders\EventBuilder;
use App\Builders\UserBuilder;
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

    /**
     * @return LengthAwarePaginator<Event>
     */
    #[Computed]
    public function events(): LengthAwarePaginator
    {
        $user = Auth::userOrFail();

        return Event::query()
            ->with('users')
            ->when(! Gate::allows('viewAll', Event::class), function (EventBuilder $query) use ($user): void {
                $query->whereHasUsers(function (UserBuilder $query) use ($user): void {
                    $query->whereKey($user->id);
                });
            })
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
            placeholder="{{ __('Search ...') }}"
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
            <flux:table.column>{{ __('Adresa') }}</flux:table.column>
            <flux:table.column>{{ __('Děti') }}</flux:table.column>
            <flux:table.column>{{ __('Bublináři') }}</flux:table.column>
            <flux:table.column
                sortable
                :sorted="$sortBy === 'start_at'"
                :direction="$sortDirection->value"
                wire:click="sort('start_at')"
            >
                {{ __('Datum') }}
            </flux:table.column>
            <flux:table.column>{{ __('Čas') }}</flux:table.column>
            <flux:table.column>{{ __('Stav') }}</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->events as $event)
                <livewire:events.table-row :event="$event" :key="$event->id" />
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
