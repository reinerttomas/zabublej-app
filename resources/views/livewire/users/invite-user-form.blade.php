<?php

declare(strict_types=1);

use App\Actions\CreateInvitationAction;
use App\Enums\Livewire\DialogName;
use App\Enums\Livewire\LivewireEvent;
use App\Enums\Role;
use App\Models\Invitation;
use App\Models\User;
use App\Notifications\InvitationRegisterNotification;
use App\ValueObjects\InvitationPayload;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component
{
    #[Validate(['required', 'string', 'max:255'])]
    public string $name = '';

    #[Validate(['required', 'string', 'email', 'max:255'])]
    public string $email = '';

    #[Validate(['required', new Enum(Role::class)])]
    public Role $role;

    #[Validate(['nullable', 'string', 'max:255'])]
    public ?string $description = null;

    public function invite(CreateInvitationAction $createInvitation): void
    {
        Gate::authorize('invite', User::class);

        $this->validate();

        $createInvitation->execute($this->all());

        $this->reset();

        $this->dispatch(LivewireEvent::InvitationCreated);

        Flux::toast('Sent.', variant: 'success');
    }
}; ?>

<form wire:submit="invite" class="space-y-6">
    <div>
        <flux:heading size="lg">{{ __('Invite User') }}</flux:heading>
        <flux:subheading>
            {{ __('Invite new user to join your team by sending them an email invitation. Assign a role to define their access level.') }}
        </flux:subheading>
    </div>

    <flux:input label="{{ __('Name') }}" wire:model="name" />
    <flux:input label="{{ __('Email') }}" wire:model="email" />

    <flux:select variant="combobox" label="{{ __('Role') }}" placeholder="Choose role..." wire:model="role">
        @foreach (Role::roles() as $role)
            <flux:select.option :value="$role->value">{{ $role->name }}</flux:select.option>
        @endforeach
    </flux:select>

    <flux:textarea
        label="{{ __('Description') }}"
        placeholder="Add a personal note to your invitation"
        wire:model="description"
        badge="Optional"
    />

    <div class="flex gap-2">
        <flux:spacer />

        <flux:modal.close>
            <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
        </flux:modal.close>

        <flux:button type="submit" variant="primary">{{ __('Invite') }}</flux:button>
    </div>
</form>
