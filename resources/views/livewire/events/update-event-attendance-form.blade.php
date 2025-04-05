<?php

declare(strict_types=1);

use App\Actions\Events\CreateEventAttendanceAction;
use App\Actions\Events\DeleteEventAttendanceAction;
use App\Builders\EventAttendanceBuilder;
use App\Builders\EventBuilder;
use App\Enums\Livewire\DialogName;
use App\Enums\Permission;
use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\User;
use App\Notifications\Events\EventAttendanceDeletedNotification;
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
            ->whereDoesntHaveEventAttendances(function (EventAttendanceBuilder $query): void {
                $query->whereEventId($this->event->id);
            })
            ->orderBy('name')
            ->get();
    }

    /**
     * @return Collection<int, EventAttendance>
     */
    #[Computed]
    public function eventAttendances(): Collection
    {
        return EventAttendance::query()
            ->with('user')
            ->whereEventId($this->event->id)
            ->get();
    }

    public function showDialogAddUser(): void
    {
        $this->modal(DialogName::EventAttendanceCreate)->show();
    }

    public function createEventAttendance(): void
    {
        Gate::authorize('update', $this->event);

        $this->validate([
            'userId' => ['required', 'exists:users,id'],
        ]);

        $user = User::findOrFail($this->userId);

        app(CreateEventAttendanceAction::class)->execute($this->event, $user);

        $this->reset('userId');

        $this->modal(DialogName::EventAttendanceCreate)->close();

        Flux::toast('Uloženo.', variant: 'success');
    }

    public function deleteEventAttendance(int $userId): void
    {
        Gate::authorize('update', $this->event);

        $user = User::findOrFail($userId);

        app(DeleteEventAttendanceAction::class)->execute($this->event, $user);

        Flux::toast('Uloženo.', variant: 'success');
    }
}; ?>

<div class="space-y-6">
    <flux:heading size="lg">{{ __('Pracovníci') }}</flux:heading>
    <flux:subheading>{{ __('Správa bublinářů pro tuto událost') }}</flux:subheading>

    @can('add-worker', $event)
        <flux:button variant="primary" class="w-full" wire:click="showDialogAddUser">
            {{ __('Přidat pracovníka') }}
        </flux:button>

        <flux:modal name="{{ DialogName::EventAttendanceCreate }}" class="w-full max-w-lg">
            <form wire:submit="createEventAttendance" class="space-y-6">
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

    @foreach ($this->eventAttendances() as $eventAttendance)
        <flux:card class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <flux:heading size="lg">{{ $eventAttendance->user->name }}</flux:heading>
                </div>
                <div>
                    <x-badge-event-attendance
                        :color="$eventAttendance->status->badge()"
                        :label="$eventAttendance->status->label()"
                    />
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <flux:text class="flex items-center gap-2">
                        <flux:icon.mail variant="micro" />
                        {{ $eventAttendance->user->email }}
                    </flux:text>
                    <flux:text class="flex items-center gap-2">
                        <flux:icon.phone variant="micro" />
                        {{ $eventAttendance->user->phone }}
                    </flux:text>
                </div>
                <div>
                    @can('remove-worker', $event)
                        <flux:button
                            variant="ghost"
                            icon="trash-2"
                            wire:click="deleteEventAttendance({{ $eventAttendance->user->id }})"
                        />
                    @endcan
                </div>
            </div>
        </flux:card>
    @endforeach
</div>
