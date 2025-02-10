<?php

declare(strict_types=1);

use App\Actions\Auth\SendVerificationEmailAction;
use App\Actions\Profile\UpdateProfileInformationAction;
use App\Enums\LivewireEvent;
use App\Models\User;
use App\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    public function mount(): void
    {
        $this->name = Auth::userOrFail()->name;
        $this->email = Auth::userOrFail()->email;
    }

    public function updateProfileInformation(UpdateProfileInformationAction $updateProfileInformation): void
    {
        $user = Auth::userOrFail();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $updateProfileInformation->execute($user, $validated);

        $this->dispatch(LivewireEvent::ProfileInformationUpdated, name: $user->name);

        Flux::toast('Profile updated successfully.', variant: 'success');
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(SendVerificationEmailAction $sendVerificationEmail): void
    {
        $user = Auth::userOrFail();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $sendVerificationEmail->execute($user);

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<form wire:submit="updateProfileInformation" class="space-y-6">
    <div>
        <flux:heading size="lg">{{ __('Profile Information') }}</flux:heading>
        <flux:subheading>{{ __("Update your account's profile information and email address.") }}</flux:subheading>
    </div>

    <div class="space-y-6">
        <flux:input
            wire:model="name"
            label="{{ __('Name') }}"
            type="text"
            placeholder="{{ __('Your name') }}"
            required
        />

        <flux:input
            wire:model="email"
            label="{{ __('Email') }}"
            type="email"
            placeholder="{{ __('Your email address') }}"
            required
        />

        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
            <div>
                <p class="text-sm font-medium text-zinc-500 dark:text-white/70">
                    {{ __('Your email address is unverified.') }}

                    <flux:link wire:click="sendVerification" href="#" :accent="false">
                        {{ __('Click here to re-send the verification email.') }}
                    </flux:link>
                </p>
            </div>
        @endif
    </div>

    <div class="flex items-center gap-4">
        <flux:button type="submit" variant="primary">{{ __('Update Profile') }}</flux:button>
    </div>
</form>
