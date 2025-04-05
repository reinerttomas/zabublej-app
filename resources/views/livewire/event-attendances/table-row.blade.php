<?php

declare(strict_types=1);

use App\Enums\EventAttendanceStatus;
use App\Enums\Livewire\DialogName;
use App\Enums\Livewire\LivewireEvent;
use App\Enums\Permission;
use App\Models\Event;
use App\Models\EventAttendance;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component
{
    public EventAttendance $eventAttendance;

    public function showDialogReject(): void
    {
        $this->modal(DialogName::EventAttendanceReject)->show();
    }

    public function pending(): void
    {
        Gate::authorize('update', $this->eventAttendance);

        $this->setStatus(EventAttendanceStatus::Pending);
    }

    public function confirm(): void
    {
        Gate::authorize('update', $this->eventAttendance);

        $this->setStatus(EventAttendanceStatus::Confirmed);
    }

    public function reject(): void
    {
        $this->setStatus(EventAttendanceStatus::Rejected);

        $this->modal(DialogName::EventAttendanceReject)->close();
    }

    private function setStatus(EventAttendanceStatus $status): void
    {
        $this->eventAttendance->setStatus($status);
        $this->eventAttendance->save();

        $this->dispatch(LivewireEvent::EventAttendancesRefresh);

        Flux::toast('Uloženo.', variant: 'success');
    }
}; ?>

<flux:table.row>
    <flux:table.cell>{{ $eventAttendance->user->name }}</flux:table.cell>
    <flux:table.cell>
        <flux:link variant="subtle" href="{{ route('events.show', $eventAttendance->event) }}" wire:navigate>
            {{ $eventAttendance->event->name }}
        </flux:link>
    </flux:table.cell>
    <flux:table.cell>{{ $eventAttendance->event->start_at->translatedFormatDate() }}</flux:table.cell>
    <flux:table.cell>{{ $eventAttendance->event->start_at->translatedFormatTime() }}</flux:table.cell>

    <flux:table.cell>
        <x-badge-event-attendance
            :color="$eventAttendance->status->badge()"
            :label="$eventAttendance->status->label()"
        />
    </flux:table.cell>

    <flux:table.cell>{{ $eventAttendance->created_at->translatedFormatDate() }}</flux:table.cell>

    @can('update', $eventAttendance)
        <flux:table.cell class="flex gap-2">
            @switch($eventAttendance->status)
                @case(EventAttendanceStatus::Pending)
                    <flux:button size="sm" wire:click="confirm">Schválit</flux:button>
                    <flux:button size="sm" wire:click="showDialogReject" class="text-red-600! dark:text-red-500!">
                        Zamítnout
                    </flux:button>

                    <flux:modal name="{{ DialogName::EventAttendanceReject }}" class="w-full max-w-lg">
                        <form wire:submit="reject">
                            <div class="space-y-6">
                                <div>
                                    <flux:heading size="lg">
                                        {{ __('Opravdu chcete zamítnout tuto přihlášku?') }}
                                    </flux:heading>
                                </div>

                                <div class="space-y-2">
                                    <flux:text>{{ $eventAttendance->event->name }}</flux:text>
                                    <flux:text>{{ $eventAttendance->user->name }}</flux:text>
                                </div>

                                <div class="flex gap-2">
                                    <flux:spacer />
                                    <flux:modal.close>
                                        <flux:button variant="ghost">{{ __('Zrušit') }}</flux:button>
                                    </flux:modal.close>
                                    <flux:button type="submit" variant="danger">{{ __('Zamítnout') }}</flux:button>
                                </div>
                            </div>
                        </form>
                    </flux:modal>

                    @break
                @case(EventAttendanceStatus::Rejected)
                    <flux:button size="sm" wire:click="pending">
                        {{ __('Obnovit') }}
                    </flux:button>

                    @break
            @endswitch
        </flux:table.cell>
    @endcan
</flux:table.row>
