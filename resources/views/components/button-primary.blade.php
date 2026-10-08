@props(['size' => 'md'])

<x-button :variant="'primary'" :size="$size" {{ $attributes }}>{{ $slot }}</x-button>