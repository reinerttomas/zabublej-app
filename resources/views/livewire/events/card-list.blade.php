<?php

declare(strict_types=1);

use App\Builders\EventAttendanceBuilder;
use App\Builders\EventBuilder;
use App\Builders\UserBuilder;
use App\Enums\EventStatus;
use App\Enums\EventTab;
use App\Enums\Livewire\LivewireEvent;
use App\Models\Event;
use App\Queries\GetAvailableEventsQuery;
use App\Queries\GetMyEventsQuery;
use App\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;

use function Amp\Dns\query;

new class extends Component
{
    public int $perPage = 5;

    #[Url]
    public EventTab $eventTab = EventTab::MyEvents;

    /**
     * @return Collection<Event>
     */
    #[Computed]
    public function events(): Collection
    {

        return Event::query()
            ->withPendingWorkersCount()
            ->withApprovedWorkersCount()
            ->when($this->eventTab->isMy(), function (EventBuilder $query): void {
                $query->tap(new GetMyEventsQuery);
            })
            ->when($this->eventTab->isAvailable(), function (EventBuilder $query): void {
                $query->tap(new GetAvailableEventsQuery);
            })
            ->take($this->perPage)
            ->get();
    }

    #[Computed]
    public function myEventsCount(): int
    {
        return Event::query()
            ->tap(new GetMyEventsQuery)
            ->count();
    }

    #[Computed]
    public function availableEventsCount(): int
    {
        return Event::query()
            ->tap(new GetAvailableEventsQuery)
            ->count();
    }

    #[Computed]
    public function maxEvents(): int
    {
        return match ($this->eventTab) {
            EventTab::MyEvents => $this->myEventsCount,
            EventTab::AvailableEvents => $this->AvailableEventsCount,
        };
    }

    public function loadMore(): void
    {
        $this->perPage += 5;
    }

    public function resetPerPage(): void
    {
        $this->perPage = 5;
    }

    #[On(LivewireEvent::EventsRefresh->value)]
    public function refresh(): void
    {
        // Způsobí kompletní překreslení komponenty
    }
}; ?>

<div class="space-y-4">
    <flux:tabs variant="segmented" wire:model="eventTab" wire:change="resetPerPage" class="w-full">
        <flux:tab name="{{ EventTab::MyEvents }}" icon="calendar-check-2">
            <span class="hidden sm:inline">{{ __('Moje události') }}</span>
            <span class="sm:hidden">{{ __('Moje') }}</span>
            <span
                class="rounded-full bg-zinc-400/15 px-2.5 py-0.5 text-xs font-medium text-zinc-700 dark:bg-white/10 dark:text-zinc-200"
            >
                {{ $this->myEventsCount }}
            </span>
        </flux:tab>
        <flux:tab name="{{ EventTab::AvailableEvents }}" icon="calendar-clock">
            <span class="hidden sm:inline">{{ __('Dostupné události') }}</span>
            <span class="sm:hidden">{{ __('Dostupné') }}</span>
            <span
                class="rounded-full bg-zinc-400/15 px-2.5 py-0.5 text-xs font-medium text-zinc-700 dark:bg-white/10 dark:text-zinc-200"
            >
                {{ $this->AvailableEventsCount }}
            </span>
        </flux:tab>
    </flux:tabs>

    <div class="space-y-4">
        @foreach ($this->events as $event)
            <livewire:events.card :event="$event" :wire:key="'event-card-'.$event->id" />
        @endforeach
    </div>

    @if ($this->maxEvents > $this->perPage)
        <div class="flex justify-center py-4" x-intersect.full="$wire.loadMore()">
            <flux:button class="w-48">{{ __('Načíst další') }}</flux:button>
        </div>
    @endif
</div>
