<?php

declare(strict_types=1);

use App\Actions\Events\UpdateEventAction;
use App\Builders\EventBuilder;
use App\Builders\UserBuilder;
use App\Enums\EventStatus;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new class extends Component
{
    public Event $event;

    public int $assignUserId;

    /**
     * @return \Illuminate\Support\Collection<\App\Models\User>
     */
    #[Computed]
    public function users(): Collection
    {
        return User::select('id', 'first_name', 'last_name')
            ->whereDoesntHaveEvents(function (EventBuilder $query): void {
                $query->whereKey($this->event->id);
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection<\App\Models\User>
     */
    #[Computed]
    public function usersByLastNameAndFirstName(): Collection
    {
        return $this->event->users()
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->get();
    }

    public function assign(): void
    {
        $this->validate([
            'assignUserId' => ['required', 'exists:users,id'],
        ]);

        $this->event->users()->attach($this->assignUserId);

        $this->reset('assignUserId');

        Flux::toast('Saved.', variant: 'success');
    }

    public function unassign(int $userId): void
    {
        $this->event->users()->detach($userId);

        Flux::toast('Removed.', variant: 'success');
    }
}; ?>

<div class="space-y-6">
    <form wire:submit="assign" class="space-y-6">
        <flux:heading size="lg">Users</flux:heading>

        <flux:select
            variant="listbox"
            searchable
            placeholder="Choose user..."
            wire:model="assignUserId"
            wire:change="assign"
        >
            @foreach ($this->users as $user)
                <flux:select.option :value="$user->id">{{ $user->fullname }}</flux:select.option>
            @endforeach
        </flux:select>
    </form>

    <flux:separator />

    <div class="grid gap-6">
        @foreach ($this->usersByLastNameAndFirstName() as $user)
            <div class="flex items-center justify-between space-x-4">
                <div>
                    <p class="text-base font-medium text-zinc-800 dark:text-white">{{ $user->fullname }}</p>
                    <p class="text-sm text-zinc-500 dark:text-white/70">{{ $user->email }}</p>
                </div>
                <div>
                    <flux:button size="sm" variant="danger" icon="x-mark" wire:click="unassign({{ $user->id }})" />
                </div>
            </div>
        @endforeach
    </div>
</div>
