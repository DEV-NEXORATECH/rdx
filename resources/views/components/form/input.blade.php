@props([
    'name' => null,
    'type' => 'text',
    'value' => null,
    'autofocus' => false,
    'inlinePrefix' => null,
    'inlineSuffix' => null,
])

<div @class(['relative', 'w-full' => $inlinePrefix || $inlineSuffix])>
    @if ($inlinePrefix)
        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-muted">{{ $inlinePrefix }}</span>
    @endif

    <input
        @if ($name) name="{{ $name }}" id="{{ $name }}" @endif
        type="{{ $type }}"
        @if ($autofocus) autofocus @endif
        @if ($value) value="{{ $value }}" @endif
        {{ $attributes->merge(['class' => 'input-base focus-ring']) }}
        @class(['pl-9' => $inlinePrefix])
    >

    @if ($inlineSuffix)
        <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-sm text-muted">{{ $inlineSuffix }}</span>
    @endif
</div>