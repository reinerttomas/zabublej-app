<?php

declare(strict_types=1);

use App\Actions\Auth\LogoutAction;
use App\Actions\Auth\SendVerificationEmailAction;
use App\Livewire\Actions\Logout;
use App\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(SendVerificationEmailAction $sendVerificationEmail): void
    {
        $user = Auth::userOrFail();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        $sendVerificationEmail->execute($user);

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(LogoutAction $logout): void
    {
        $logout->execute();

        $this->redirect('/', navigate: true);
    }
}; ?>

<flux:card>
    <form wire:submit="sendVerification" class="space-y-6">
        <div>
            <flux:heading size="lg">{{ __('Verify your email') }}</flux:heading>
            <flux:subheading>
                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </flux:subheading>
        </div>

        <!-- Session Status -->
        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 text-sm font-medium text-green-600 dark:text-green-500">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="space-y-2">
            <flux:button wire:click="sendVerification" variant="primary" class="w-full">
                {{ __('Resend Verification Email') }}
            </flux:button>
            <flux:button wire:click="logout" variant="ghost" class="w-full">
                {{ __('Log Out') }}
            </flux:button>
        </div>
    </form>
</flux:card>
