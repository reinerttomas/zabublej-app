<x-layouts.app>
    <div class="mx-auto max-w-xl lg:max-w-4xl">
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">{{ __('Přehled událostí') }}</flux:heading>
            <flux:subheading size="lg" class="mb-6">
                {{ __('Prohlížejte a přihlašujte se na nadcházející události') }}
            </flux:subheading>

            <flux:separator variant="subtle" />
        </div>

        <livewire:events.card-list />
    </div>
</x-layouts.app>
