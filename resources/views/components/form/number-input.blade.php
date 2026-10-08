@props(['name' => null, 'value' => null, 'min' => null, 'max' => null, 'step' => 'any', 'placeholder' => null])

<input
    @if ($name) name="{{ $name }}" @endif
    type="number"
    inputmode="decimal"
    @if ($min !== null) min="{{ $min }}" @endif
    @if ($max !== null) max="{{ $max }}" @endif
    step="{{ $step }}"
    @if ($placeholder) placeholder="{{ $placeholder }}" @endif
    value="{{ $value }}"
    {{ $attributes->merge(['class' => 'input-base focus-ring tabular-nums']) }}
>