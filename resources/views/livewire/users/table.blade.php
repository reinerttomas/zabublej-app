<?php

declare(strict_types=1);

use App\Builders\UserBuilder;
use App\Enums\Database\Direction;
use App\Enums\Livewire\DialogName;
use App\Enums\Permission;
use App\Livewire\WithPagination;
use App\Livewire\WithSearching;
use App\Livewire\WithSorting;
use App\Models\User;
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
        $this->defaultSortBy('id');
        $this->defaultSortDirection(Direction::ASC);
    }

    /**
     * @return LengthAwarePaginator<User>
     */
    #[Computed]
    public function users(): LengthAwarePaginator
    {
        return User::query()
            ->when($this->isSearchSet(), function (UserBuilder $query): void {
                $query->search($this->search);
            })
            ->orderByDirection($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function delete(int $id): void
    {
        $user = User::findOrFail($id);

        Gate::authorize('delete', $user);

        $user->delete();

        $this->modal(DialogName::UserDelete)->close();

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

    <flux:table :paginate="$this->users" :perPage="$perPage">
        <flux:table.columns>
            <flux:table.column
                sortable
                :sorted="$sortBy === 'name'"
                :direction="$sortDirection->value"
                wire:click="sort('name')"
            >
                {{ __('Jméno') }}
            </flux:table.column>
            <flux:table.column
                sortable
                :sorted="$sortBy === 'email'"
                :direction="$sortDirection->value"
                wire:click="sort('email')"
            >
                {{ __('Email') }}
            </flux:table.column>
            <flux:table.column>{{ __('Telefon') }}</flux:table.column>
            <flux:table.column>{{ __('Poslední přihlášení') }}</flux:table.column>
            <flux:table.column>{{ __('Stav') }}</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->users as $user)
                <livewire:users.table-row :user="$user" :key="$user->id" />
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
