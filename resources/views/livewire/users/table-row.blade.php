<?php

declare(strict_types=1);

use App\Enums\Livewire\DialogName;
use App\Enums\Permission;
use App\Enums\Role;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component
{
    public User $user;

    #[Validate(['required', 'string', 'max:255'])]
    public string $name = '';

    #[Validate(['required', 'string', 'email', 'max:255'])]
    public string $email = '';

    #[Validate(['nullable', 'string', 'max:50'])]
    public ?string $phone = null;

    public function mount(): void
    {
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
    }

    public function showDialogUpdate(): void
    {
        $this->modal(DialogName::UserUpdate)->show();
    }

    public function showDialogDelete(): void
    {
        $this->modal(DialogName::UserDelete)->show();
    }

    public function update(): void
    {
        Gate::authorize('update', $this->user);

        $this->validate();

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => now(),
            'phone' => $this->phone,
        ]);

        $this->modal(DialogName::UserUpdate)->close();

        Flux::toast('Uloženo.', variant: 'success');
    }
}; ?>

<flux:table.row>
    <flux:table.cell>{{ $user->name }}</flux:table.cell>
    <flux:table.cell>{{ $user->email }}</flux:table.cell>
    <flux:table.cell>{{ $user->phone }}</flux:table.cell>
    <flux:table.cell>{{ $user->last_login_at?->translatedFormatDateTime() }}</flux:table.cell>
    <flux:table.cell>
        @if ($user->deleted_at)
            <flux:badge color="red" size="sm">{{ __('Neaktivní') }}</flux:badge>
        @else
            <flux:badge color="lime" size="sm">{{ __('Aktivní') }}</flux:badge>
        @endif
    </flux:table.cell>

    @canany(['update', 'delete'], $user)
        <flux:table.cell>
            <flux:dropdown>
                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>

                <flux:menu>
                    @can('update', $user)
                        <flux:menu.item class="justify-between" wire:click="showDialogUpdate">
                            <div>{{ __('Upravit') }}</div>
                            <flux:icon.square-pen variant="micro" />
                        </flux:menu.item>
                    @endcan

                    <flux:menu.separator />

                    @can('delete', $user)
                        <flux:menu.item class="justify-between" variant="danger" wire:click="showDialogDelete">
                            <div>{{ __('Smazat') }}</div>
                            <flux:icon.trash-2 variant="micro" />
                        </flux:menu.item>
                    @endcan
                </flux:menu>
            </flux:dropdown>

            @can('update', $user)
                <flux:modal name="{{ DialogName::UserUpdate }}" class="w-full max-w-lg">
                    <form wire:submit="update" class="space-y-6">
                        <div>
                            <flux:heading size="lg">{{ __('Upravit uživatele') }}</flux:heading>
                        </div>

                        <flux:input label="{{ __('Jméno') }}" wire:model="name" />
                        <flux:input type="email" label="{{ __('Email') }}" wire:model="email" />
                        <flux:input label="{{ __('Telefon') }}" wire:model="phone" />

                        <div class="flex gap-2">
                            <flux:spacer />

                            <flux:modal.close>
                                <flux:button variant="ghost">{{ __('Zrušit') }}</flux:button>
                            </flux:modal.close>

                            <flux:button type="submit" variant="primary">{{ __('Uložit') }}</flux:button>
                        </div>
                    </form>
                </flux:modal>
            @endcan

            @can('delete', $user)
                <flux:modal name="{{ DialogName::UserDelete }}" class="w-full max-w-lg">
                    <form class="space-y-6" wire:submit="$parent.delete({{ $user->id }})">
                        <div class="space-y-6">
                            <flux:heading size="lg">
                                {{ __('Opravdu chcete odstranit uživatele?') }}
                            </flux:heading>

                            <div class="space-y-2">
                                <flux:text>{{ $user->name }}</flux:text>
                                <flux:text>{{ $user->email }}</flux:text>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <flux:spacer />

                            <flux:modal.close>
                                <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                            </flux:modal.close>

                            <flux:button type="submit" variant="danger">{{ __('Delete') }}</flux:button>
                        </div>
                    </form>
                </flux:modal>
            @endcan
        </flux:table.cell>
    @endcanany
</flux:table.row>
