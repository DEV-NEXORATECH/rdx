@props([
    'name' => null,
    'options' => [],
    'value' => null,
    'placeholder' => null,
    'allowEmpty' => true,
])

<select
    @if ($name) name="{{ $name }}" @endif
    {{ $attributes->merge(['class' => 'focus-ring input-base appearance-none rounded-lg pr-9']) }}
>
    @if ($allowEmpty)
        <option value="" @selected($value === null || $value === '')>{{ $placeholder ?? '— Pilih —' }}</option>
    @endif

    @foreach ($options as $optionValue => $optionLabel)
        @if (is_array($optionLabel))
            <optgroup label="{{ $optionValue }}">
                @foreach ($optionLabel as $groupValue => $groupLabel)
                    <option value="{{ $groupValue }}" @selected((string) $value === (string) $groupValue)>{{ $groupLabel }}</option>
                @endforeach
            </optgroup>
        @else
            @php
                $optValue = is_integer($optionValue) ? (string) $optionLabel : $optionValue;
            @endphp
            <option value="{{ $optValue }}" @selected((string) $value === (string) $optValue)>{{ $optionLabel }}</option>
        @endif
    @endforeach
</select>