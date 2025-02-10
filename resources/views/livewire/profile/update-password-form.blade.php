<?php

declare(strict_types=1);

use App\Actions\Auth\UpdatePasswordAction;
use App\Enums\LivewireEvent;
use App\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function updatePassword(UpdatePasswordAction $updatePassword): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset();

            throw $e;
        }

        $updatePassword->execute(Auth::userOrFail(), $this->password);

        $this->reset();

        Flux::toast('Password updated successfully.', variant: 'success');
    }
}; ?>

<form wire:submit="updatePassword" class="space-y-6">
    <div>
        <flux:heading size="lg">{{ __('Update Password') }}</flux:heading>
        <flux:subheading>
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </flux:subheading>
    </div>

    <div class="space-y-6">
        <flux:input
            wire:model="current_password"
            label="{{ __('Current Password') }}"
            type="password"
            required
            viewable
        />
        <flux:input wire:model="password" label="{{ __('New Password') }}" type="password" required viewable />
        <flux:input
            wire:model="password_confirmation"
            label="{{ __('Confirm Password') }}"
            type="password"
            required
            viewable
        />
    </div>

    <div class="flex items-center gap-4">
        <flux:button type="submit" variant="primary">{{ __('Update Password') }}</flux:button>
    </div>
</form>
