@props(['intent' => 'neutral', 'size' => 'sm'])

<x-badge :intent="$intent" :size="$size" dot {{ $attributes }}>{{ $slot }}</x-badge>