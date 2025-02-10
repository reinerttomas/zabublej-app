<?php

declare(strict_types=1);

use App\Actions\Auth\ConfirmPasswordAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Validate(['required', 'string', 'current_password'])]
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(ConfirmPasswordAction $confirmPassword): void
    {
        $this->validate();

        $confirmPassword->execute();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<flux:card>
    <form wire:submit="confirmPassword" class="space-y-6">
        <div>
            <flux:heading size="lg">{{ __('Confirm your password') }}</flux:heading>
            <flux:subheading>
                {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
            </flux:subheading>
        </div>

        <div class="space-y-6">
            <flux:input
                wire:model="password"
                label="{{ __('Password') }}"
                type="password"
                placeholder="{{ __('Your password') }}"
                required
                autofocus
            />
        </div>

        <flux:button type="submit" variant="primary" class="w-full">
            {{ __('Confirm') }}
        </flux:button>
    </form>
</flux:card>
