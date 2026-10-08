@props(['label' => 'Ekspor', 'icon' => 'arrow-down-tray'])

<x-button variant="secondary" :icon="$icon" {{ $attributes }}>{{ $label }}</x-button>