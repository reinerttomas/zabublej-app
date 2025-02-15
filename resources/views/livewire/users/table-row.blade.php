<?php

declare(strict_types=1);

use App\Actions\User\UpdateUserAction;
use App\Livewire\Modal;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public User $user;

    public string $firstName;
    public string $lastName;
    #[Locked]
    public string $email;
    public ?string $phone = null;

    public function mount(): void
    {
        $this->firstName = $this->user->first_name;
        $this->lastName = $this->user->last_name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
    }

    public function edit(): void
    {
        $this->modal(Modal::UserUpdate)->show();
    }

    public function update(UpdateUserAction $updateUser): void
    {
        $this->validate([
            'firstName' => ['required', 'string', 'max:50'],
            'lastName' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', Rule::unique(User::class, 'email')->ignore($this->user->id), 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        $updateUser->execute($this->user, [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'phone' => $this->phone,
        ]);

        Flux::toast(sprintf('User %s has been updated.', $this->user->email), variant: 'success');

        $this->modal(Modal::UserUpdate)->close();
    }

    public function remove(): void
    {
        $this->modal(Modal::UserDelete)->show();
    }
}; ?>

<flux:row>
    <flux:cell>{{ $user->fullname }}</flux:cell>
    <flux:cell>{{ $user->email }}</flux:cell>
    <flux:cell>{{ $user->phone }}</flux:cell>
    <flux:cell>{{ $user->last_login_at?->toHuman() }}</flux:cell>
    <flux:cell>
        @if ($user->deleted_at)
            <flux:badge color="red" size="sm">{{ __('Inactive') }}</flux:badge>
        @else
            <flux:badge color="lime" size="sm">{{ __('Active') }}</flux:badge>
        @endif
    </flux:cell>
    <flux:cell>
        <flux:dropdown>
            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>

            <flux:menu>
                <flux:menu.item wire:click="edit" icon="pencil">{{ __('Edit') }}</flux:menu.item>
                <flux:menu.item wire:click="remove" icon="trash" variant="danger">{{ __('Remove') }}</flux:menu.item>
            </flux:menu>
        </flux:dropdown>

        <flux:modal name="{{ Modal::UserUpdate }}" variant="flyout" class="">
            <form wire:submit="update" class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Edit user') }}</flux:heading>
                    <flux:subheading>{{ __('Update a user') }}</flux:subheading>
                </div>

                <flux:input label="{{ __('First name') }}" wire:model="firstName" />
                <flux:input label="{{ __('Last name') }}" wire:model="lastName" />
                <flux:input type="email" label="{{ __('Email') }}" wire:model="email" disabled />
                <flux:input label="{{ __('Phone') }}" wire:model="phone" />

                <div class="flex gap-2">
                    <flux:spacer />

                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </flux:modal.close>

                    <flux:button type="submit" variant="primary">{{ __('Update user') }}</flux:button>
                </div>
            </form>
        </flux:modal>

        <flux:modal name="{{ Modal::UserDelete }}" class="min-w-[22rem]">
            <form class="space-y-6" wire:submit="$parent.delete({{ $user->id }})">
                <div>
                    <flux:heading size="lg">{{ __('Delete user?') }}</flux:heading>

                    <flux:subheading>
                        <p>{{ __("You're about to delete this user.") }}</p>
                        <p>{{ __('This action cannot be reversed.') }}</p>
                    </flux:subheading>
                </div>

                <div class="flex gap-2">
                    <flux:spacer />

                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </flux:modal.close>

                    <flux:button type="submit" variant="danger">{{ __('Delete user') }}</flux:button>
                </div>
            </form>
        </flux:modal>
    </flux:cell>
</flux:row>
