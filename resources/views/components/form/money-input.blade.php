@props(['name' => null, 'value' => null, 'placeholder' => '0', 'currency' => 'IDR'])

<div class="relative w-full">
    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm font-medium text-muted">
        {{ $currency === 'IDR' ? 'Rp' : $currency }}
    </span>
    <input
        @if ($name) name="{{ $name }}" @endif
        type="text"
        inputmode="decimal"
        autocomplete="off"
        placeholder="{{ $placeholder }}"
        value="{{ $value }}"
        {{ $attributes->merge(['class' => 'input-base focus-ring pl-9 text-right font-medium tabular-nums']) }}
    >
</div>