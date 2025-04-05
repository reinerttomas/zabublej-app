<?php

declare(strict_types=1);

use App\Enums\EventAttendanceStatus;
use App\Enums\Livewire\DialogName;
use App\Enums\Livewire\LivewireEvent;
use App\Models\Event;
use App\Models\EventAttendance;
use App\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new class extends Component
{
    public Event $event;
    public ?EventAttendance $eventAttendance = null;

    public function mount(): void
    {
        $this->eventAttendance = EventAttendance::query()
            ->whereEventId($this->event->id)
            ->whereUserId(Auth::userOrFail()->id)
            ->first();
    }

    public function showDialogSignIn(): void
    {
        $this->modal(DialogName::EventRegister)->show();
    }

    public function register(): void
    {
        Gate::authorize('register', $this->event);

        // Admin will be automatically approved
        if (Gate::allows('update', $this->event)) {
            $data = [
                'status' => EventAttendanceStatus::Confirmed,
            ];
        }

        $this->event->eventAttendances()->create([
            'user_id' => Auth::userOrFail()->id,
            ...$data ?? [],
        ]);

        $this->dispatch(LivewireEvent::EventsRefresh);

        $this->modal(DialogName::EventRegister)->close();

        Flux::toast('Uloženo.', variant: 'success');
    }
}; ?>

<flux:card>
    <div class="space-y-4">
        <div class="flex flex-col space-y-2">
            <div class="flex flex-col-reverse flex-wrap items-start gap-2 md:flex-row md:justify-between">
                <flux:heading size="lg">{{ $event->name }}</flux:heading>

                @if ($eventAttendance)
                    <flux:badge color="{{ $eventAttendance->status->badge() }}" variant="pill" size="sm">
                        {{ $eventAttendance->status->label() }}
                    </flux:badge>
                @else
                    <flux:badge variant="pill" size="sm">
                        {{ $event->getCapacity()->getOccupiedCount() }}/{{ $event->getCapacity()->maxWorkers }}
                        {{ __('obsazeno') }}
                    </flux:badge>
                @endif
            </div>
            <flux:text>{{ $event->description }}</flux:text>
        </div>
        <div class="space-y-2">
            <flux:text variant="strong" class="flex items-center gap-2">
                <flux:icon.calendar-days variant="micro" />
                {{ $event->start_at->translatedFormatDate() }}
            </flux:text>
            <flux:text variant="strong" class="flex items-center gap-2">
                <flux:icon.clock variant="micro" />
                {{ $event->start_at->translatedFormatTime() }}
            </flux:text>
            <flux:text variant="strong" class="flex items-center gap-2">
                <flux:icon.map-pin variant="micro" />
                {{ $event->location }}
            </flux:text>
            @if ($event->reward)
                <flux:text variant="strong" class="flex items-center gap-2">
                    <flux:icon.coins variant="micro" />
                    {{ $event->formattedReward() }}
                </flux:text>
            @endif
        </div>

        @canany(['view', 'register'], $event)
            <div class="flex items-center gap-2">
                @can('view', $event)
                    <flux:button href="{{ route('events.show', $event) }}" size="sm" wire:navigate>
                        Zobrazit detail
                    </flux:button>
                @endcan

                @can('register', $event)
                    <flux:button variant="primary" size="sm" wire:click="showDialogSignIn">
                        {{ __('Přihlásit se') }}
                    </flux:button>

                    <flux:modal name="{{ DialogName::EventRegister }}" class="w-full max-w-lg">
                        <form wire:submit="register" class="space-y-6">
                            <flux:heading size="lg">{{ $event->name }}</flux:heading>

                            <div class="space-y-2">
                                <flux:text variant="strong" class="flex items-center gap-2">
                                    <flux:icon.calendar-days variant="micro" />
                                    {{ $event->start_at->translatedFormatDate() }}
                                </flux:text>
                                <flux:text variant="strong" class="flex items-center gap-2">
                                    <flux:icon.clock variant="micro" />
                                    {{ $event->start_at->translatedFormatTime() }}
                                </flux:text>
                                <flux:text variant="strong" class="flex items-center gap-2">
                                    <flux:icon.map-pin variant="micro" />
                                    {{ $event->location }}
                                </flux:text>
                                <flux:text variant="strong" class="flex items-center gap-2">
                                    <flux:icon.coins variant="micro" />
                                    {{ $event->formattedReward() }}
                                </flux:text>
                            </div>

                            <div class="flex gap-2">
                                <flux:spacer />

                                <flux:modal.close>
                                    <flux:button variant="ghost">{{ __('Zrušit') }}</flux:button>
                                </flux:modal.close>

                                <flux:button type="submit" variant="primary">
                                    {{ __('Odeslat přihlášku') }}
                                </flux:button>
                            </div>
                        </form>
                    </flux:modal>
                @endcan
            </div>
        @endcanany
    </div>
</flux:card>
