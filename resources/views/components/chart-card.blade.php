@props([
    'title' => null,
    'description' => null,
    'value' => null,
    'footer' => null,
    'height' => 'h-64',
])

<div {{ $attributes->class(['overflow-hidden rounded-xl border border-border bg-surface shadow-sm']) }}>
    <div class="flex items-start justify-between gap-4 border-b border-border px-5 py-4">
        <div class="min-w-0">
            @if ($title)
                <h2 class="text-base font-semibold text-heading">{{ $title }}</h2>
            @endif
            @if ($description)
                <p class="mt-0.5 text-sm text-muted">{{ $description }}</p>
            @endif
        </div>

        @if ($value ?? null)
            <div class="shrink-0 text-right">
                <span class="text-lg font-bold tabular-nums text-heading">{{ $value }}</span>
            </div>
        @endif
    </div>

    <div class="{{ $height }} px-3 py-3">
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="border-t border-border px-5 py-3 text-sm text-muted">{{ $footer }}</div>
    @endisset
</div>