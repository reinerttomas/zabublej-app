<x-layouts.app>
    <div class="mx-auto max-w-xl lg:max-w-4xl">
        <div class="relative w-full">
            <flux:heading size="xl">{{ __('Přehled událostí') }}</flux:heading>
            <flux:subheading>
                {{ __('Prohlížejte a přihlašujte se na nadcházející události') }}
            </flux:subheading>

            <flux:separator variant="subtle" class="my-6" />
        </div>

        <livewire:events.card-list />
    </div>
</x-layouts.app>
