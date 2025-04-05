<?php

declare(strict_types=1);

use App\Enums\Livewire\DialogName;
use App\Enums\Permission;
use App\Models\Event;
use App\Models\EventAttendance;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new class extends Component
{
    public function mount(): void
    {
        Gate::authorize('viewAny', EventAttendance::class);
    }
}; ?>

<section class="w-full space-y-6">
    <div class="relative w-full">
        <div>
            <flux:heading size="xl">{{ __('Docházka') }}</flux:heading>
            <flux:subheading>
                {{ __('Schválit nebo zamítnout přihlášky bublinářů na události') }}
            </flux:subheading>
        </div>

        <flux:separator variant="subtle" class="my-6" />
    </div>

    <div class="space-y-4">
        @if (session('success'))
            <flux:callout variant="success" icon="check-circle" heading="{{ session('success') }}" />
        @endif

        @if (session('warning'))
            <flux:callout variant="warning" icon="exclamation-circle" heading="{{ session('warning') }}" />
        @endif

        @if (session('error'))
            <flux:callout variant="danger" icon="x-circle" heading="{{ session('error') }}" />
        @endif
    </div>

    <livewire:event-attendances.table />
</section>
