<?php

declare(strict_types=1);

use App\Actions\CreateInvitationAction;
use App\Enums\Livewire\DialogName;
use App\Enums\Livewire\LivewireEvent;
use App\Enums\Role;
use App\Models\Invitation;
use App\Models\User;
use App\Notifications\Invitation\InvitationRegisterNotification;
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
        <flux:heading size="lg">{{ __('Odeslat pozvánku') }}</flux:heading>
        <flux:subheading>
            {{ __('Pozvěte nového uživatele do týmu zasláním e-mailové pozvánky. Přiřazením role určete úroveň jejich přístupu.') }}
        </flux:subheading>
    </div>

    <flux:input label="{{ __('Jméno') }}" wire:model="name" />
    <flux:input label="{{ __('Email') }}" wire:model="email" />

    <flux:select variant="combobox" label="{{ __('Role') }}" placeholder="Zvolte..." wire:model="role">
        @foreach (Role::roles() as $role)
            <flux:select.option :value="$role->value">{{ $role->label() }}</flux:select.option>
        @endforeach
    </flux:select>

    <flux:textarea
        label="{{ __('Popis') }}"
        placeholder="Napište k pozvánce osobní poznámku"
        wire:model="description"
        badge="Nepovinné"
    />

    <div class="flex gap-2">
        <flux:spacer />

        <flux:modal.close>
            <flux:button variant="ghost">{{ __('Zrušit') }}</flux:button>
        </flux:modal.close>

        <flux:button type="submit" variant="primary">{{ __('Odelat pozvánku') }}</flux:button>
    </div>
</form>
