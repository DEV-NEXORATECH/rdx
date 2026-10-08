@props([
    'label' => '',
    'value' => null,
    'icon' => null,
    'hint' => null,
    'trend' => null,
    'trendDirection' => 'up',
    'trendPositive' => true,
    'loading' => false,
])

<div {{ $attributes->merge(['class' => 'relative overflow-hidden rounded-xl border border-border bg-surface p-5 shadow-sm']) }}>
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="truncate text-sm font-medium text-muted">{{ $label }}</p>

            @if ($loading)
                <div class="mt-2 h-8 w-28 animate-pulse rounded bg-slate-100"></div>
            @else
                <p class="mt-1.5 text-2xl font-bold tracking-tight text-heading tabular-nums">{{ $value }}</p>
            @endif

            <div class="mt-2 flex items-center gap-2">
                @if ($trend)
                    <span @class([
                        'inline-flex items-center gap-1 rounded-full px-1.5 py-0.5 text-xs font-semibold',
                        'bg-success-soft text-success' => $trendDirection === 'up' && $trendPositive,
                        'bg-danger-soft text-danger' => $trendDirection === 'up' && ! $trendPositive,
                        'bg-success-soft text-success' => $trendDirection === 'down' && ! $trendPositive,
                        'bg-danger-soft text-danger' => $trendDirection === 'down' && $trendPositive,
                    ])>
                        <x-icon :name="$trendDirection === 'up' ? 'arrow-up-tray' : 'arrow-down-tray'" class="h-3 w-3" />
                        {{ $trend }}
                    </span>
                @endif

                @if ($hint)
                    <span class="text-xs text-muted">{{ $hint }}</span>
                @endif
            </div>
        </div>

        @if ($icon)
            <span @class([
                'flex h-10 w-10 shrink-0 items-center justify-center rounded-lg',
                'bg-primary-soft text-primary',
            ])>
                <x-icon :name="$icon" class="h-5 w-5" />
            </span>
        @endif
    </div>

    {{ $slot }}
</div>