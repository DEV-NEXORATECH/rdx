@props(['size' => 'md'])

<x-button :variant="'danger'" :size="$size" {{ $attributes }}>{{ $slot }}</x-button>