<?php

declare(strict_types=1);

use App\Builders\EventBuilder;
use App\Enums\Livewire\DialogName;
use App\Enums\Permission;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new class extends Component
{
    public Event $event;

    public int $userId;

    /**
     * @return Collection<int, User>
     */
    #[Computed]
    public function availableUsers(): Collection
    {
        return User::select('id', 'name')
            ->whereDoesntHaveEvents(function (EventBuilder $query): void {
                $query->whereKey($this->event->id);
            })
            ->orderBy('name')
            ->get();
    }

    /**
     * @return Collection<int, User>
     */
    #[Computed]
    public function users(): Collection
    {
        return $this->event->users()
            ->orderBy('name')
            ->get();
    }

    public function showDialogAddUser(): void
    {
        $this->modal(DialogName::EventAddUser)->show();
    }

    public function addUser(): void
    {
        $this->authorize(Permission::UpdateEvent, $this->event);

        $this->validate([
            'userId' => ['required', 'exists:users,id'],
        ]);

        $this->event->users()->attach($this->userId);

        $this->reset('userId');

        $this->modal(DialogName::EventAddUser)->close();

        Flux::toast('Saved.', variant: 'success');
    }

    public function removeUser(int $userId): void
    {
        $this->authorize(Permission::UpdateEvent, $this->event);

        $this->event->users()->detach($userId);

        Flux::toast('Removed.', variant: 'success');
    }
}; ?>

<div class="space-y-6">
    <flux:heading size="lg">{{ __('Pracovníci') }}</flux:heading>
    <flux:subheading>{{ __('Správa pracovníků pro tuto událost') }}</flux:subheading>

    @can(Permission::UpdateEvent)
        <flux:button variant="primary" class="w-full" wire:click="showDialogAddUser">
            {{ __('Přidat pracovníka') }}
        </flux:button>
    @endcan

    @can(Permission::UpdateEvent)
        <flux:modal name="{{ DialogName::EventAddUser }}" class="w-full max-w-lg">
            <form wire:submit="addUser" class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Přidat pracovníka') }}</flux:heading>
                </div>

                <flux:select
                    variant="listbox"
                    searchable
                    placeholder="{{ __('Vybrat pracovníka...') }}"
                    wire:model="userId"
                >
                    @foreach ($this->availableUsers() as $user)
                        <flux:select.option :value="$user->id">{{ $user->name }}</flux:select.option>
                    @endforeach
                </flux:select>

                <div class="flex gap-2">
                    <flux:spacer />

                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Zrušit') }}</flux:button>
                    </flux:modal.close>

                    <flux:button type="submit" variant="primary">{{ __('Přidat pracovníka') }}</flux:button>
                </div>
            </form>
        </flux:modal>
    @endcan

    @foreach ($this->users as $user)
        <flux:card>
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-base font-medium text-zinc-800 dark:text-white">{{ $user->name }}</div>
                    <div class="text-sm text-zinc-500 dark:text-white/70">{{ $user->email }}</div>
                    <div class="text-sm text-zinc-500 dark:text-white/70">{{ $user->phone }}</div>
                </div>
                <div>
                    <flux:button variant="ghost" icon="trash-2" wire:click="removeUser({{ $user->id }})" />
                </div>
            </div>
        </flux:card>
    @endforeach
</div>
