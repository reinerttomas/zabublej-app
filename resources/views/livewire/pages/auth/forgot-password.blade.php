<?php

declare(strict_types=1);

use App\Actions\Auth\SendPasswordResetLinkAction;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Validate(['required', 'string', 'email'])]
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(SendPasswordResetLinkAction $sendPasswordResetLink): void
    {
        $this->validate();

        try {
            $status = $sendPasswordResetLink->execute($this->email);
        } catch (ValidationException $e) {
            $this->addError('email', $e->getMessage());

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<flux:card>
    <form wire:submit="sendPasswordResetLink" class="space-y-6">
        <div>
            <flux:heading size="lg">{{ __('Reset your password') }}</flux:heading>
            <flux:subheading>{{ __('Enter your email to receive a password reset link') }}</flux:subheading>
        </div>

        <!-- Session Status -->
        <x-auth-session-status :status="session('status')" />

        <div class="space-y-6">
            <flux:input
                wire:model="email"
                label="{{ __('Email') }}"
                type="email"
                placeholder="{{ __('Your email address') }}"
                required
                autofocus
            />
        </div>

        <div class="space-y-2">
            <flux:button variant="primary" class="w-full" type="submit">
                {{ __('Email Password Reset Link') }}
            </flux:button>

            <flux:button variant="ghost" class="w-full" href="{{ route('login') }}" wire:navigate>
                {{ __('Back to login') }}
            </flux:button>
        </div>
    </form>
</flux:card>
