<?php

declare(strict_types=1);

use App\Builders\UserBuilder;
use App\Livewire\WithPagination;
use App\Livewire\WithSearching;
use App\Livewire\WithSorting;
use App\Models\User;
use App\Queries\SortByQuery;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    use WithPagination;
    use WithSearching;
    use WithSorting;

    public User $user;

    #[Computed]
    public function users(): LengthAwarePaginator
    {
        $search = Str::of($this->search)->trim();

        return User::query()
            ->when($search->isNotEmpty(), function (UserBuilder $builder): void {
                $builder->search($this->search);
            })
            ->tap(new SortByQuery($this->sortBy, $this->sortDirection))
            ->paginate($this->perPage);
    }

    public function delete(int $id): void
    {
        User::findOrFail($id)->delete();
    }
}; ?>

<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:justify-between">
        <flux:input
            class="md:max-w-sm"
            icon="magnifying-glass"
            placeholder="{{ __('Search ...') }}"
            wire:model.live.debounce.300ms="search"
        />

        <x-per-page :per-page="$perPage" />
    </div>

    <flux:table :paginate="$this->users">
        <flux:columns>
            <flux:column
                sortable
                :sorted="$sortBy === 'last_name'"
                :direction="$sortDirection->value"
                wire:click="sort('last_name')"
            >
                {{ __('Name') }}
            </flux:column>
            <flux:column
                sortable
                :sorted="$sortBy === 'email'"
                :direction="$sortDirection->value"
                wire:click="sort('email')"
            >
                {{ __('Email') }}
            </flux:column>
            <flux:column>{{ __('Phone') }}</flux:column>
            <flux:column>{{ __('Last login at') }}</flux:column>
            <flux:column>{{ __('Status') }}</flux:column>
        </flux:columns>

        <flux:rows>
            @foreach ($this->users as $user)
                <livewire:users.table-row :user="$user" :key="$user->id" />
            @endforeach
        </flux:rows>
    </flux:table>
</div>
