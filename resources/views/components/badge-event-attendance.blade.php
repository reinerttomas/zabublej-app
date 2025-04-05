@props([
    'color' => null,
    'label' => null,
])

<flux:badge color="{{ $color }}" size="sm">{{ $label }}</flux:badge>
