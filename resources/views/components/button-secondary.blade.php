@props(['size' => 'md'])

<x-button :variant="'secondary'" :size="$size" {{ $attributes }}>{{ $slot }}</x-button>