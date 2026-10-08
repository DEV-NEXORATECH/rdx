@props(['name' => null, 'value' => null, 'min' => null, 'max' => null, 'placeholder' => null])

<input
    @if ($name) name="{{ $name }}" @endif
    type="date"
    @if ($min) min="{{ $min }}" @endif
    @if ($max) max="{{ $max }}" @endif
    @if ($placeholder) placeholder="{{ $placeholder }}" @endif
    value="{{ $value }}"
    {{ $attributes->merge(['class' => 'focus-ring input-base rounded-lg']) }}
>