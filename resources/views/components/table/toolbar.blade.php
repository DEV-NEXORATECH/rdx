@props(['count' => null])

<div {{ $attributes->class('mb-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between') }}>
    <div class="flex flex-1 flex-wrap items-center gap-2">
        @if (isset($left))
            {{ $left }}
        @endif
        {{ $slot }}
    </div>

    @if (isset($right) && trim((string) $right))
        <div class="flex flex-wrap items-center gap-2">
            {{ $right }}
        </div>
    @endif
</div>