<?php

use App\Actions\Auth\LogoutAction;
use App\Actions\Users\DeleteUserAction;
use App\Livewire\DialogName;
use App\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(DeleteUserAction $deleteUser): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        $deleteUser->execute(Auth::userOrFail());

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="space-y-6">
    <div>
        <flux:heading size="lg">{{ __('Delete Account') }}</flux:heading>
        <flux:subheading>
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}
        </flux:subheading>
    </div>

    <flux:modal.trigger name="{{ DialogName::UserDelete }}">
        <flux:button variant="danger" class="mt-4">{{ __('Delete Account') }}</flux:button>
    </flux:modal.trigger>

    <flux:modal name="{{ DialogName::UserDelete }}" class="min-w-[22rem] space-y-6">
        <form wire:submit="deleteUser">
            <div>
                <flux:heading size="lg">{{ __('Are you sure you want to delete your account?') }}</flux:heading>

                <flux:subheading>
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}
                </flux:subheading>
            </div>

            <div class="mt-6">
                <flux:input
                    wire:model="delete_password"
                    label="{{ __('Password') }}"
                    type="password"
                    placeholder="{{ __('Your password') }}"
                    required
                />
            </div>

            <div class="mt-6 flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="danger">{{ __('Delete Account') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
