<?php

declare(strict_types=1);

use App\Enums\Permission;
use App\Models\Event;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new class extends Component
{
    public Event $event;

    public function mount(): void
    {
        Gate::authorize('view', $this->event);
    }
}; ?>

<section class="w-full">
    <div class="relative mb-6 w-full">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">{{ $event->name }}</flux:heading>
                <flux:subheading size="lg" class="mb-6">{{ __('Manage your events and attendees') }}</flux:subheading>
            </div>
            <div class="flex items-end gap-4">
                @can('update', $this->event)
                    <flux:button variant="primary" href="{{ route('events.edit', $event) }}" wire:navigate>
                        {{ __('Upravit') }}
                    </flux:button>
                @endcan
            </div>
        </div>
        <flux:separator variant="subtle" />
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <flux:card class="space-y-6">
                <div class="space-y-6">
                    <div class="flex flex-col space-y-4">
                        <div class="flex flex-col items-start justify-between gap-2 sm:flex-row sm:items-center">
                            <flux:heading size="lg">{{ __('Základní informace') }}</flux:heading>
                            <flux:badge color="{{ $event->status->badge() }}">
                                {{ $event->status->label() }}
                            </flux:badge>
                        </div>
                        <div>
                            {!! $event->description !!}
                        </div>
                    </div>
                </div>

                <flux:separator variant="subtle" />

                <div class="space-y-6">
                    <div class="space-y-4">
                        <flux:heading size="lg">{{ __('Datum a čas') }}</flux:heading>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="flex items-center gap-2">
                                <span>{{ $event->start_at->translatedFormatDate() }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span>{{ $event->start_at->translatedFormatTime() }}</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            {{ $event->location }}
                        </div>
                    </div>
                </div>

                <flux:separator variant="subtle" />

                <div class="space-y-6">
                    <div class="space-y-4">
                        <flux:heading size="lg">{{ __('Účastníci') }}</flux:heading>
                        <div class="flex items-center gap-2">
                            <flux:icon.circle-check variant="mini" />
                            <div>{{ __('Událost je pro více osob') }}</div>
                        </div>
                        <div class="flex items-center gap-2">
                            {{ __('Počet děti:') }}
                            <strong>{{ $event->estimated_children_count }}</strong>
                        </div>
                    </div>
                </div>

                <flux:separator variant="subtle" />

                <div class="space-y-6">
                    <div class="space-y-4">
                        <flux:heading size="lg">{{ __('Finanční informace') }}</flux:heading>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            @can('update', $this->event)
                                <div class="flex items-center gap-2">
                                    <div>{{ __('Cena události:') }}</div>
                                    <div class="font-bold">{{ $event->price }}&nbsp;Kč</div>
                                </div>
                            @endcan

                            <div class="flex items-center gap-2">
                                <div>{{ __('Odměna pro pracovníka:') }}</div>
                                <div class="font-bold">{{ $event->reward }}&nbsp;Kč</div>
                            </div>
                        </div>
                    </div>
                </div>

                <flux:separator variant="subtle" />

                <div class="space-y-6">
                    <div class="space-y-4">
                        <flux:heading size="lg">{{ __('Kontaktní informace') }}</flux:heading>

                        <div class="flex items-center gap-2">
                            <div>{{ __('Kontaktní osoba:') }}</div>
                            <div class="font-bold">{{ $event->contact_person }}</div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            @can('update', $this->event)
                                <div class="flex items-center gap-2">
                                    <div>{{ $event->contact_email }}</div>
                                </div>
                            @endcan

                            <div class="flex items-center gap-2">
                                <div>{{ $event->contact_phone }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <flux:separator variant="subtle" />

                <div class="space-y-4">
                    <flux:heading size="lg">{{ __('Poznámky') }}</flux:heading>
                    <div>
                        {{ $event->note }}
                    </div>
                </div>
            </flux:card>
        </div>
        <div>
            <flux:card>
                <div class="space-y-6">
                    <flux:heading size="lg">{{ __('Pracovníci') }}</flux:heading>
                    <flux:subheading>{{ __('Správa pracovníků pro tuto událost') }}</flux:subheading>

                    @foreach ($event->users as $user)
                        <flux:card>
                            <div class="text-base font-medium text-zinc-800 dark:text-white">
                                {{ $user->name }}
                            </div>
                            <div class="text-sm text-zinc-500 dark:text-white/70">{{ $user->email }}</div>
                            <div class="text-sm text-zinc-500 dark:text-white/70">{{ $user->phone }}</div>
                        </flux:card>
                    @endforeach
                </div>
            </flux:card>
        </div>
    </div>
</section>
