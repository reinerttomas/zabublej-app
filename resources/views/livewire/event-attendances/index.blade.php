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

<section class="w-full">
    <div class="relative mb-6 w-full">
        <div>
            <flux:heading size="xl" level="1">{{ __('Docházka') }}</flux:heading>
            <flux:subheading size="lg" class="mb-6">
                {{ __('Schválit nebo zamítnout přihlášky bublinářů na události') }}
            </flux:subheading>
        </div>
        <flux:separator variant="subtle" />
    </div>

    <livewire:event-attendances.table />
</section>
