<?php

use App\Actions\Auth\LogoutAction;
use App\Enums\LivewireEvent;
use App\Models\User;
use App\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component
{
    #[Locked]
    public User $user;

    public function mount(): void
    {
        $this->user = Auth::userOrFail();
    }

    public function logout(LogoutAction $logout): void
    {
        $logout->execute();

        $this->redirectRoute('login', navigate: true);
    }

    #[On(LivewireEvent::ProfileInformationUpdated->value)]
    public function refreshProfile(): void
    {
        $this->user = Auth::userOrFail();
    }
}; ?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxStyles
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-zinc-800">
        <!-- Page Heading -->
        <flux:sidebar
            sticky
            stashable
            class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900"
        >
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <flux:brand
                href="{{ route('dashboard') }}"
                wire:navigate
                logo="https://fluxui.dev/img/demo/logo.png"
                name="Acme Inc."
                class="px-2 dark:hidden"
            />
            <flux:brand
                href="{{ route('dashboard') }}"
                wire:navigate
                logo="https://fluxui.dev/img/demo/dark-mode-logo.png"
                name="Acme Inc."
                class="hidden px-2 dark:flex"
            />

            <flux:navlist variant="outline">
                <flux:navlist.item
                    icon="home"
                    href="{{ route('dashboard') }}"
                    :current="request()->routeIs('dashboard')"
                    wire:navigate
                >
                    {{ __('Home') }}
                </flux:navlist.item>
                <flux:navlist.item
                    icon="users"
                    href="{{ route('users.index') }}"
                    :current="request()->routeIs('users.index')"
                    wire:navigate
                >
                    {{ __('Users') }}
                </flux:navlist.item>
                <flux:navlist.item
                    icon="calendar-days"
                    href="{{ route('events.index') }}"
                    :current="request()->routeIs('events.index')"
                    wire:navigate
                >
                    {{ __('Events') }}
                </flux:navlist.item>
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item
                    icon="cog-6-tooth"
                    href="{{ route('settings') }}"
                    :current="request()->routeIs('settings')"
                    wire:navigate
                >
                    Settings
                </flux:navlist.item>
                <flux:navlist.item icon="information-circle" href="#">Help</flux:navlist.item>
            </flux:navlist>

            @volt('layout.sidebar.profile.dropdown')
                <flux:dropdown position="top" align="start" class="max-lg:hidden">
                    <flux:profile name="{{ $user->name }}" />

                    <flux:menu>
                        <flux:navmenu.item href="{{ route('profile') }}" wire:navigate icon="user">
                            {{ __('Account') }}
                        </flux:navmenu.item>

                        <flux:menu.separator />

                        <flux:menu.item wire:click="logout" icon="arrow-right-start-on-rectangle">
                            {{ __('Logout') }}
                        </flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            @endvolt
        </flux:sidebar>

        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            @volt('layout.header.profile.dropdown')
                <flux:dropdown position="top" alignt="start">
                    <flux:profile avatar="https://fluxui.dev/img/demo/user.png" />

                    <flux:menu>
                        <flux:navmenu.item href="{{ route('profile') }}" wire:navigate icon="user">
                            {{ __('Account') }}
                        </flux:navmenu.item>

                        <flux:menu.separator />

                        <flux:menu.item wire:click="logout" icon="arrow-right-start-on-rectangle">
                            {{ __('Logout') }}
                        </flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            @endvolt
        </flux:header>

        <!-- Page Content -->
        <flux:main>
            {{ $slot }}
        </flux:main>

        @persist('toast')
            <flux:toast />
        @endpersist

        @fluxScripts()
    </body>
</html>
