<?php

declare(strict_types=1);

use App\Actions\RegisterUserAction;
use App\Events\Login;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component
{
    public Invitation $invitation;

    public string $name = '';
    public string $email = '';
    public string $phone = '+420';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(string $token): void
    {
        $invitation = Invitation::findOrFail($token);

        if ($invitation->isAccepted()) {
            abort(403, trans('Tato pozvánka již byla použita.'));
        }

        if ($invitation->isExpired()) {
            abort(403, trans('Platnost této pozvánky vypršela.'));
        }

        $this->invitation = $invitation;
        $this->name = $this->invitation->payload->name;
        $this->email = $this->invitation->email;
    }

    public function register(RegisterUserAction $registerUser): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $registerUser->execute($this->invitation, $validated);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header title="Create an account" description="Enter your details below to create your account" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <flux:input
            wire:model="name"
            id="name"
            label="{{ __('Name') }}"
            type="text"
            name="name"
            required
            autofocus
            autocomplete="name"
            placeholder="Full name"
        />

        <!-- Email Address -->
        <flux:input
            wire:model="email"
            id="email"
            label="{{ __('Email address') }}"
            type="email"
            name="email"
            required
            autocomplete="email"
            placeholder="email@example.com"
        />

        <!-- Phone -->
        <flux:input
            wire:model="phone"
            id="phone"
            label="{{ __('Phone') }}"
            type="text"
            name="phone"
            required
            autocomplete="phone"
            placeholder="Phone"
            mask="+999 999 999 999"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            id="password"
            label="{{ __('Password') }}"
            type="password"
            name="password"
            required
            autocomplete="new-password"
            placeholder="Password"
            viewable
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            id="password_confirmation"
            label="{{ __('Confirm password') }}"
            type="password"
            name="password_confirmation"
            required
            autocomplete="new-password"
            placeholder="Confirm password"
            viewable
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
        Already have an account?
        <flux:link href="{{ route('login') }}" wire:navigate>Log in</flux:link>
    </div>
</div>
