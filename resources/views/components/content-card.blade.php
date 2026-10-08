@props([
    'title' => null,
    'description' => null,
    'padding' => true,
    'overflowVisible' => false,
])

<div {{ $attributes->class(['rounded-xl border border-border bg-surface shadow-sm']) }}>
    @if ($title || (isset($header) && trim((string) $header)) || (isset($actions) && trim((string) $actions)))
        <div class="flex items-start justify-between gap-4 border-b border-border px-5 py-4">
            <div class="min-w-0">
                @if ($title)
                    <h2 class="text-base font-semibold text-heading">{{ $title }}</h2>
                @endif
                @isset($description)
                    <p class="mt-0.5 text-sm text-muted">{{ $description }}</p>
                @endisset
                {{ $header ?? '' }}
            </div>
            @isset($actions)
                <div class="flex shrink-0 items-center gap-2">{{ $actions }}</div>
            @endisset
        </div>
    @endif

    <div @class(['overflow-hidden' => ! $overflowVisible, ($padding ? 'p-5' : '')])>
        {{ $slot }}
    </div>
</div>