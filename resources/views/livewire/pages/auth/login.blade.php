<?php

declare(strict_types=1);

use App\Actions\Auth\LoginByEmailAndPasswordAction;
use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Validate(['required', 'string', 'email'])]
    public string $email = '';

    #[Validate(['required', 'string'])]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    public function login(LoginByEmailAndPasswordAction $loginByEmailAndPassword): void
    {
        $this->validate();

        $loginByEmailAndPassword->execute(...$this->all());

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<flux:card>
    <form wire:submit="login" class="space-y-6">
        <div>
            <flux:heading size="lg">{{ __('Log in to your account') }}</flux:heading>
            <flux:subheading>{{ __('Welcome back!') }}</flux:subheading>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <div class="space-y-6">
            <flux:input
                wire:model="email"
                label="{{ __('Email') }}"
                type="email"
                placeholder="{{ __('Your email address') }}"
                autofocus
            />

            <flux:field>
                <flux:label class="flex justify-between">
                    {{ __('Password') }}

                    <flux:link href="{{ route('password.request') }}" wire:navigate variant="subtle">
                        {{ __('Forgot your password?') }}
                    </flux:link>
                </flux:label>

                <flux:input wire:model="password" type="password" placeholder="{{ __('Your password') }}" />

                <flux:error name="password" />
            </flux:field>

            <flux:checkbox wire:model="remember" label="{{ __('Remember me') }}" />
        </div>

        <flux:button variant="primary" class="w-full" type="submit">{{ __('Log in') }}</flux:button>
    </form>
</flux:card>
