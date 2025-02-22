<?php

declare(strict_types=1);

use App\Actions\Events\CreateEventAction;
use App\Actions\Events\UpdateEventAction;
use App\Models\Event;
use Carbon\CarbonImmutable;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component
{
    #[Validate('required', 'string', 'max:255')]
    public string $name = '';

    #[Validate('required', 'date', 'after:today')]
    public string $startDate = '';

    #[Validate('required', 'date_format:H:i')]
    public string $startTime = '';

    public function create(CreateEventAction $createEvent): void
    {
        $this->validate();

        $event = $createEvent->execute([
            'name' => $this->name,
            'start_at' => CarbonImmutable::parse($this->startTime . '' . $this->startDate),
        ]);

        Flux::toast('Event has been created.', variant: 'success');

        $this->redirect(route('events.update', $event), navigate: true);
    }
}; ?>

<form wire:submit="create" class="space-y-6">
    <div>
        <flux:heading size="lg">{{ __('Create event') }}</flux:heading>
        <flux:subheading>{{ __('Creating a event') }}</flux:subheading>
    </div>

    <flux:input label="{{ __('Name') }}" wire:model="name" />

    <div class="grid gap-4 sm:grid-cols-2">
        <flux:input type="date" label="Start Date" wire:model="startDate" />
        <flux:input type="time" label="Start Time" wire:model="startTime" />
    </div>

    <div class="flex gap-2">
        <flux:spacer />

        <flux:modal.close>
            <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
        </flux:modal.close>

        <flux:button type="submit" variant="primary">{{ __('Create event') }}</flux:button>
    </div>
</form>
