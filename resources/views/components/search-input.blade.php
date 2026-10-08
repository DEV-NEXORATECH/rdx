@props(['label' => 'Lakukan pencarian'])

<label class="relative block w-full sm:w-64">
    <span class="sr-only">{{ $label }}</span>
    <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted" />
    <input
        type="search"
        placeholder="{{ $label }}"
        {{ $attributes->merge(['class' => 'focus-ring input-base rounded-lg py-2 pl-9']) }}
    >
</label>