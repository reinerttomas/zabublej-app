<?php

declare(strict_types=1);

use App\Enums\Livewire\DialogName;
use App\Enums\Livewire\LivewireEvent;
use App\Enums\Permission;
use App\Models\Event;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component
{
    public Event $event;

    #[Validate(['required', 'string', 'max:255'])]
    public string $name = '';

    #[Validate(['nullable', 'string', 'max:65535'])]
    public ?string $description = null;

    #[Validate(['required', 'date'])]
    public string $startDate = '';

    #[Validate(['required', 'date_format:H:i'])]
    public string $startTime = '';

    #[Validate(['nullable', 'string', 'max:255'])]
    public ?string $location = null;

    #[Validate(['nullable', 'string', 'max:100'])]
    public ?string $contactPerson = null;

    #[Validate(['nullable', 'email', 'max:200'])]
    public ?string $contactEmail = null;

    #[Validate(['nullable', 'string', 'max:20'])]
    public ?string $contactPhone = null;

    #[Validate(['boolean'])]
    public bool $isMultiPerson = false;

    #[Validate(['nullable', 'integer', 'min:0'])]
    public ?int $childrenCount = null;

    #[Validate(['nullable', 'integer', 'min:1'])]
    public ?int $workersCount = null;

    #[Validate(['nullable', 'integer', 'min:1'])]
    public ?float $price = null;

    #[Validate(['nullable', 'integer', 'min:1'])]
    public ?float $reward = null;

    #[Validate(['nullable', 'string'])]
    public ?string $note = null;

    public function mount(): void
    {
        $this->authorize(Permission::UpdateEvent, $this->event);

        $this->name = $this->event->name;
        $this->description = $this->event->description;
        $this->startDate = $this->event->start_at->format('Y-m-d');
        $this->startTime = $this->event->start_at->format('H:i');
        $this->location = $this->event->location;
        $this->contactPerson = $this->event->contact_person;
        $this->contactEmail = $this->event->contact_email;
        $this->contactPhone = $this->event->contact_phone;
        $this->isMultiPerson = $this->event->is_multi_person;
        $this->childrenCount = $this->event->children_count;
        $this->workersCount = $this->event->workers_count;
        $this->price = $this->event->price;
        $this->reward = $this->event->reward;
        $this->note = $this->event->note;
    }

    public function update(): void
    {
        Gate::authorize('update', $this->event);

        $this->validate();

        $this->event->fill([
            'name' => $this->name,
            'description' => $this->description,
            'start_at' => CarbonImmutable::parse($this->startDate . ' ' . $this->startTime),
            'location' => $this->location,
            'contact_person' => $this->contactPerson,
            'contact_email' => $this->contactEmail,
            'contact_phone' => $this->contactPhone,
            'is_multi_person' => $this->isMultiPerson,
            'children_count' => $this->childrenCount,
            'workers_count' => $this->workersCount,
            'price' => $this->price,
            'reward' => $this->reward,
            'note' => $this->note,
        ]);
        $this->event->save();

        $this->dispatch(LivewireEvent::EventUpdated);

        Flux::toast('Saved.', variant: 'success');
    }

    #[On(LivewireEvent::EventUpdated->value)]
    public function refresh(): void
    {
        $this->event->refresh();
    }
}; ?>

<form wire:submit="update" class="space-y-6">
    <div class="space-y-6">
        <div class="flex flex-col items-start justify-between gap-2 sm:flex-row sm:items-center">
            <flux:heading size="lg">{{ __('Základní informace') }}</flux:heading>
            <flux:badge color="{{ $event->status->badge() }}">
                {{ $event->status->label() }}
            </flux:badge>
        </div>

        <flux:input label="{{ __('Název') }}" wire:model="name" />
        <flux:editor label="{{ __('Popis') }}" wire:model="description" />
    </div>

    <flux:separator variant="subtle" />

    <div class="space-y-6">
        <flux:heading size="lg">{{ __('Datum, čas a místo') }}</flux:heading>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <flux:input type="date" label="{{ __('Datum') }}" wire:model="startDate" />
            <flux:input type="time" label="{{ __('Čas') }}" wire:model="startTime" />
        </div>

        <flux:input label="{{ __('Lokace') }}" wire:model="location" />
    </div>

    <flux:separator variant="subtle" />

    <div class="space-y-6">
        <flux:heading size="lg">{{ __('Účastníci') }}</flux:heading>

        <flux:checkbox label="{{ __('Událost je pro více osob') }}" wire:model="isMultiPerson" />

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <flux:input label="{{ __('Počet dětí') }}" wire:model="childrenCount" />
            <flux:input label="{{ __('Počet pracovníků') }}" wire:model="workersCount" />
        </div>
    </div>

    <flux:separator variant="subtle" />

    <div class="space-y-6">
        <flux:heading size="lg">{{ __('Finanční informace') }}</flux:heading>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <flux:input label="{{ __('Cena události') }}" wire:model="price" />
            <flux:input label="{{ __('Odměna pro pracovníka') }}" wire:model="reward" />
        </div>
    </div>

    <flux:separator variant="subtle" />

    <div class="space-y-6">
        <flux:heading size="lg">{{ __('Kontaktní informace') }}</flux:heading>

        <flux:input label="{{ __('Kontaktní osoba') }}" wire:model="contactPerson" />

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <flux:input type="email" label="{{ __('Email') }}" wire:model="contactEmail" />
            <flux:input label="{{ __('Telefon') }}" wire:model="contactPhone" />
        </div>
    </div>

    <flux:separator variant="subtle" />

    <div class="space-y-4">
        <flux:heading size="lg">{{ __('Poznámky') }}</flux:heading>

        <flux:textarea wire:model="note" />
    </div>

    <div class="flex items-center justify-end gap-4">
        <flux:button href="{{ route('events.index') }}">{{ __('Zrušit') }}</flux:button>
        <flux:button type="submit" variant="primary">{{ __('Uložit') }}</flux:button>
    </div>
</form>
