@props([
    'label' => null,
    'name' => null,
    'help' => null,
    'required' => false,
    'errorKey' => null,
])

@php
    $key = $errorKey ?? $name;
    $hasError = $key !== null && $errors->has($key);
    $errorMessage = $hasError ? $errors->first($key) : null;
@endphp

<div {{ $attributes->merge(['class' => 'space-y-1.5']) }}>
    @if ($label)
        <label for="{{ $name }}" @class(['text-sm font-medium', 'text-heading' => ! $hasError, 'text-danger' => $hasError])>
            {{ $label }}
            @if ($required)
                <span class="text-danger" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    {{ $slot }}

    @if ($hasError)
        <p class="text-sm text-danger" role="alert">{{ $errorMessage }}</p>
    @elseif ($help)
        <p class="text-sm text-muted">{{ $help }}</p>
    @endif
</div>