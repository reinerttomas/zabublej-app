<?php

declare(strict_types=1);

use App\Enums\Livewire\DialogName;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
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
}; ?>

<section class="w-full">
    <div class="relative mb-6 w-full">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">{{ __('Users') }}</flux:heading>
                <flux:subheading size="lg" class="mb-6">
                    {{ __('Manage your profile and account settings') }}
                </flux:subheading>
            </div>
            <div>
                @can('invite', User::class)
                    <flux:button variant="primary" wire:click="showDialogInvite">{{ __('Invite User') }}</flux:button>
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
