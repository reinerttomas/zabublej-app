<?php

declare(strict_types=1);

use App\Actions\Events\UpdateEventAction;
use App\Models\Event;
use Carbon\CarbonImmutable;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component
{
    public Event $event;

    #[Validate(['required', 'string', 'max:255'])]
    public string $name = '';

    #[Validate(['nullable', 'string', 'max:65535'])]
    public ?string $description = null;

    #[Validate(['required', 'date', 'after:today'])]
    public string $startDate = '';

    #[Validate(['required', 'date_format:H:i'])]
    public string $startTime = '';

    #[Validate(['nullable', 'integer', 'min:1'])]
    public ?int $estimatedHours = null;

    #[Validate(['nullable', 'string', 'max:255'])]
    public ?string $location = null;

    public function mount(): void
    {
        $this->name = $this->event->name;
        $this->description = $this->event->description;
        $this->startDate = $this->event->start_at->format('Y-m-d');
        $this->startTime = $this->event->start_at->format('H:i');
        $this->estimatedHours = $this->event->estimated_hours;
        $this->location = $this->event->location;
    }

    public function update(UpdateEventAction $updateEvent): void
    {
        $this->validate();

        $updateEvent->execute($this->event, [
            'name' => $this->name,
            'description' => $this->description,
            'start_at' => CarbonImmutable::parse($this->startDate . ' ' . $this->startTime),
            'estimated_hours' => $this->estimatedHours,
            'location' => $this->location,
        ]);

        Flux::toast('Saved.', variant: 'success');
    }
}; ?>

<form wire:submit="update" class="space-y-6">
    <flux:heading size="lg">Details</flux:heading>

    <flux:input label="Name" wire:model="name" />
    <flux:editor label="Description" description="Short description" wire:model="description" />

    <div class="grid gap-4 sm:grid-cols-2">
        <flux:input type="date" label="Start Date" wire:model="startDate" />
        <flux:input type="time" label="Start Time" wire:model="startTime" />
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <flux:input label="Estimated Hours" wire:model="estimatedHours" />
    </div>

    <flux:input label="Location" wire:model="location" />

    <div class="flex items-center gap-4">
        <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>
    </div>
</form>
