@props(['perPage'])

<div class="flex items-center space-x-2">
    <div class="text-sm">Per page</div>
    <flux:dropdown>
        <flux:button size="sm" icon-trailing="chevron-down">{{ $perPage ?? 10 }}</flux:button>
        <flux:menu>
            <flux:menu.radio.group wire:model.live="perPage">
                <flux:menu.radio value="10" checked>10</flux:menu.radio>
                <flux:menu.radio value="20">20</flux:menu.radio>
                <flux:menu.radio value="50">50</flux:menu.radio>
                <flux:menu.radio value="100">100</flux:menu.radio>
            </flux:menu.radio.group>
        </flux:menu>
    </flux:dropdown>
</div>
