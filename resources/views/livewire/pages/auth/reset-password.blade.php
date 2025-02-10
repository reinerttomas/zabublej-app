<?php

declare(strict_types=1);

use App\Actions\Auth\ResetPasswordAction;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email')->toString();
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(ResetPasswordAction $resetPassword): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            $status = $resetPassword->execute(
                $this->only('email', 'password', 'password_confirmation', 'token'),
            );
        } catch (ValidationException $e) {
            $this->addError('email', __($e->getMessage()));
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<flux:card>
    <form wire:submit="resetPassword" class="space-y-6">
        <div>
            <flux:heading size="lg">{{ __('Reset your password') }}</flux:heading>
            <flux:subheading>{{ __('Enter your new password') }}</flux:subheading>
        </div>

        <div class="space-y-6">
            <flux:input
                wire:model="email"
                label="{{ __('Email') }}"
                type="email"
                placeholder="{{ __('Your email address') }}"
                required
                viewable
                autofocus
            />
            <flux:input
                wire:model="password"
                label="{{ __('New Password') }}"
                type="password"
                placeholder="{{ __('Your new password') }}"
                required
                viewable
            />
            <flux:input
                wire:model="password_confirmation"
                label="{{ __('Confirm Password') }}"
                type="password"
                placeholder="{{ __('Confirm your new password') }}"
                required
                viewable
            />
        </div>

        <div class="space-y-2">
            <flux:button variant="primary" class="w-full" type="submit">
                {{ __('Reset Password') }}
            </flux:button>

            <flux:button variant="ghost" class="w-full" href="{{ route('login') }}" wire:navigate>
                {{ __('Back to login') }}
            </flux:button>
        </div>
    </form>
</flux:card>
