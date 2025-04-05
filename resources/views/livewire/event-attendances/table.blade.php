<?php

declare(strict_types=1);

use App\Builders\EventBuilder;
use App\Builders\UserBuilder;
use App\Enums\Database\Direction;
use App\Enums\EventAttendanceStatus;
use App\Enums\EventStatus;
use App\Enums\Livewire\DialogName;
use App\Enums\Livewire\LivewireEvent;
use App\Enums\Permission;
use App\Livewire\WithPagination;
use App\Livewire\WithSearching;
use App\Livewire\WithSorting;
use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\User;
use App\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;

new class extends Component
{
    use WithPagination;
    use WithSorting;

    #[Url]
    public EventAttendanceStatus $status = EventAttendanceStatus::Pending;

    public function boot(): void
    {
        $this->defaultSortBy('created_at');
        $this->defaultSortDirection(Direction::DESC);
    }

    /**
     * @return LengthAwarePaginator<Event>
     */
    #[Computed]
    public function eventAttendances(): LengthAwarePaginator
    {
        return EventAttendance::query()
            ->with(['event', 'user'])
            ->where('status', $this->status)
            ->orderByDirection($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    #[Computed]
    public function pendingEventAttendanceCount(): int
    {
        return EventAttendance::query()
            ->where('status', EventAttendanceStatus::Pending)
            ->count();
    }

    public function setStatus(EventAttendanceStatus $status): void
    {
        $this->status = $status;
    }

    #[On(LivewireEvent::EventAttendancesRefresh->value)]
    public function refresh(): void {}
}; ?>

<div class="space-y-4">
    <flux:tabs variant="segmented" wire:model="status" wire:change="setStatus($event.target.value)">
        <flux:tab name="{{ EventAttendanceStatus::Pending }}" icon="hourglass">
            {{ __('Čeká na schválení') }}
            <span
                class="rounded-full bg-zinc-400/15 px-2.5 py-0.5 text-xs font-medium text-zinc-700 dark:bg-white/10 dark:text-zinc-200"
            >
                {{ $this->pendingEventAttendanceCount }}
            </span>
        </flux:tab>
        <flux:tab name="{{ EventAttendanceStatus::Confirmed }}" icon="circle-check-big">
            {{ __('Schváleno') }}
        </flux:tab>
        <flux:tab name="{{ EventAttendanceStatus::Rejected }}" icon="circle-x">{{ __('Zatmítnuto') }}</flux:tab>
    </flux:tabs>

    <flux:table :paginate="$this->eventAttendances" :perPage="$perPage">
        <flux:table.columns>
            <flux:table.column>{{ __('Bublinář') }}</flux:table.column>
            <flux:table.column>{{ __('Název') }}</flux:table.column>
            <flux:table.column>{{ __('Datum') }}</flux:table.column>
            <flux:table.column>{{ __('Čas') }}</flux:table.column>
            <flux:table.column>{{ __('Stav') }}</flux:table.column>
            <flux:table.column>{{ __('Vytvořeno') }}</flux:table.column>

            @if ($this->status->isPending())
                <flux:table.column>{{ __('Akce') }}</flux:table.column>
            @endif
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->eventAttendances as $eventAttendance)
                <livewire:event-attendances.table-row :eventAttendance="$eventAttendance" :key="$eventAttendance->id" />
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
