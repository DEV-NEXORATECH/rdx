@props(['name' => null, 'rows' => 4, 'placeholder' => null, 'value' => null])

<textarea
    @if ($name) name="{{ $name }}" @endif
    rows="{{ $rows }}"
    @if ($placeholder) placeholder="{{ $placeholder }}" @endif
    {{ $attributes->merge(['class' => 'focus-ring input-base resize-y rounded-lg']) }}
>{{ $value }}</textarea>