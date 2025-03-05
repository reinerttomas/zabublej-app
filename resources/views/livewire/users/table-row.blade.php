<?php

declare(strict_types=1);

use App\Enums\Livewire\DialogName;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component
{
    public User $user;

    #[Validate(['required', 'string', 'max:255'])]
    public string $name = '';

    #[Validate(['required', 'string', 'email', 'max:255'])]
    public string $email = '';

    #[Validate(['nullable', 'string', 'max:50'])]
    public ?string $phone = null;

    public function mount(): void
    {
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
    }

    public function showDialogUpdate(): void
    {
        $this->modal(DialogName::UserUpdate)->show();
    }

    public function showDialogDelete(): void
    {
        $this->modal(DialogName::UserDelete)->show();
    }

    public function update(): void
    {
        $this->validate();

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => now(),
            'phone' => $this->phone,
        ]);

        $this->modal(DialogName::UserUpdate)->close();

        Flux::toast('Saved.', variant: 'success');
    }
}; ?>

<flux:table.row>
    <flux:table.cell>{{ $user->name }}</flux:table.cell>
    <flux:table.cell>{{ $user->email }}</flux:table.cell>
    <flux:table.cell>{{ $user->phone }}</flux:table.cell>
    <flux:table.cell>{{ $user->last_login_at?->human() }}</flux:table.cell>
    <flux:table.cell>
        @if ($user->deleted_at)
            <flux:badge color="red" size="sm">{{ __('Inactive') }}</flux:badge>
        @else
            <flux:badge color="lime" size="sm">{{ __('Active') }}</flux:badge>
        @endif
    </flux:table.cell>
    <flux:table.cell>
        <flux:dropdown>
            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>

            <flux:menu>
                <flux:menu.item class="justify-between" wire:click="showDialogUpdate">
                    <div>{{ __('Edit') }}</div>
                    <flux:icon.square-pen variant="micro" />
                </flux:menu.item>

                <flux:menu.separator />

                <flux:menu.item class="justify-between" variant="danger" wire:click="showDialogDelete">
                    <div>{{ __('Delete') }}</div>
                    <flux:icon.trash-2 variant="micro" />
                </flux:menu.item>
            </flux:menu>
        </flux:dropdown>

        <flux:modal name="{{ DialogName::UserUpdate }}" class="w-full max-w-lg">
            <form wire:submit="update" class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Edit user') }}</flux:heading>
                    <flux:subheading>{{ __("Update the user here. Click save when you're done.") }}</flux:subheading>
                </div>

                <flux:input label="{{ __('Name') }}" wire:model="name" />
                <flux:input type="email" label="{{ __('Email') }}" wire:model="email" />
                <flux:input label="{{ __('Phone') }}" wire:model="phone" />

                <div class="flex gap-2">
                    <flux:spacer />

                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </flux:modal.close>

                    <flux:button type="submit" variant="primary">{{ __('Update') }}</flux:button>
                </div>
            </form>
        </flux:modal>

        <flux:modal name="{{ DialogName::UserDelete }}" class="w-full max-w-lg">
            <form class="space-y-6" wire:submit="$parent.delete({{ $user->id }})">
                <div>
                    <flux:heading size="lg">{{ __('Delete user?') }}</flux:heading>

                    <flux:subheading>
                        <p>
                            {{ __('Are you sure you want to delete') }}
                            <strong>{{ $user->email }}</strong>
                        </p>
                    </flux:subheading>
                </div>

                <div class="flex gap-2">
                    <flux:spacer />

                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </flux:modal.close>

                    <flux:button type="submit" variant="danger">{{ __('Delete') }}</flux:button>
                </div>
            </form>
        </flux:modal>
    </flux:table.cell>
</flux:table.row>
