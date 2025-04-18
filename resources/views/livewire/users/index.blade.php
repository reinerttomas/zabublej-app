<?php

declare(strict_types=1);

use App\Enums\Livewire\DialogName;
use App\Enums\Livewire\LivewireEvent;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component
{
    public function mount(): void
    {
        Gate::authorize('viewAny', User::class);
    }

    public function showDialogInvite(): void
    {
        $this->modal(DialogName::UserInvite)->show();
    }

    #[On(LivewireEvent::InvitationCreated->value)]
    public function closeDialogInvite(): void
    {
        $this->modal(DialogName::UserInvite)->close();
    }
}; ?>

<section class="w-full">
    <div class="relative mb-6 w-full">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">{{ __('Uživatelé') }}</flux:heading>
                <flux:subheading size="lg" class="mb-6">
                    {{ __('Správa uživatelů') }}
                </flux:subheading>
            </div>
            <div>
                @can('invite', User::class)
                    <flux:button variant="primary" wire:click="showDialogInvite">
                        {{ __('Odeslat pozvánku') }}
                    </flux:button>
                @endcan
            </div>
        </div>
        <flux:separator variant="subtle" />
    </div>

    <livewire:users.table />

    @can('invite', User::class)
        <flux:modal name="{{ DialogName::UserInvite }}" class="w-full max-w-lg">
            <livewire:users.invite-user-form />
        </flux:modal>
    @endcan
</section>
