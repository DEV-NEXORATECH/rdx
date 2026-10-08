@props(['name' => null, 'value' => '1', 'checked' => false, 'label' => null, 'description' => null])

<label class="flex cursor-pointer items-start gap-3">
    <input
        @if ($name) name="{{ $name }}" @endif
        type="checkbox"
        value="{{ $value }}"
        @checked($checked)
        {{ $attributes->merge(['class' => 'focus-ring mt-0.5 h-4 w-4 shrink-0 rounded border-border text-primary focus:ring-primary/40']) }}
    >
    @if ($label || $description)
        <span class="min-w-0">
            @if ($label)
                <span class="block text-sm font-medium text-heading">{{ $label }}</span>
            @endif
            @if ($description)
                <span class="block text-sm text-muted">{{ $description }}</span>
            @endif
        </span>
    @endif
</label>