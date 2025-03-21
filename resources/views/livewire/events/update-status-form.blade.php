<?php

declare(strict_types=1);

use App\Enums\EventStatus;
use App\Enums\Permission;
use App\Models\Event;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new class extends Component
{
    public Event $event;
    public EventStatus $status;

    public function mount(): void
    {
        $this->status = $this->event->status;
    }

    public function update(): void
    {
        $this->authorize(Permission::UpdateEvent, $this->event);

        $this->event->update([
            'status' => $this->status,
        ]);

        Flux::toast('Saved.', variant: 'success');
    }
}; ?>

<form wire:submit="update" class="space-y-6">
    <flux:heading size="lg">{{ __('Stav') }}</flux:heading>

    <flux:select variant="listbox" wire:model="status" wire:change="update">
        @foreach (EventStatus::cases() as $status)
            <flux:select.option value="{{ $status->value }}" :selected="$status->isEqual($this->status)">
                <div class="flex items-center gap-2">
                    <div class="{{ $status->color() }} size-4 rounded-full"></div>
                    <div>{{ $status->label() }}</div>
                </div>
            </flux:select.option>
        @endforeach
    </flux:select>
</form>
