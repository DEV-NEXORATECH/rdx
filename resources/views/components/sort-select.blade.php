@props(['label' => 'Urutkan'])

<label class="flex items-center gap-2">
    <span class="sr-only">{{ $label }}</span>
    {{ $slot }}
</label>