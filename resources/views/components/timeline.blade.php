@props([
    'items' => [], // [{ title, description, timestamp, icon, intent }]
    'nowrap' => false,
])

<ol {{ $attributes->class('relative space-y-5 sm:space-y-6') }}>
    @foreach ($items as $item)
        @php
            $intent = $item['intent'] ?? 'default';
            $icon = $item['icon'] ?? null;

            if ($intent === 'primary') {
                $dot = 'text-primary bg-primary-soft';
            } elseif ($intent === 'danger') {
                $dot = 'text-danger bg-danger-soft';
            } elseif ($intent === 'warning') {
                $dot = 'text-warning bg-warning-soft';
            } elseif ($intent === 'success') {
                $dot = 'text-success bg-success-soft';
            } else {
                $dot = 'text-muted bg-slate-100';
            }
        @endphp

        <li class="relative flex gap-3">
            @if (! $loop->last)
                <span class="absolute -bottom-6 left-[1.05rem] top-8 w-px bg-border"></span>
            @endif

            <span class="relative z-10 flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $dot }}">
                <x-icon :name="$icon ?? 'circle'" class="h-4 w-4" />
            </span>

            <div class="min-w-0 flex-1 pt-0.5">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <p class="text-sm font-medium leading-snug text-heading">{{ $item['title'] }}</p>
                    @if ($item['timestamp'] ?? null)
                        <time class="text-xs text-muted tabular-nums" datetime="{{ $item['timestamp'] }}">{{ $item['timestamp'] }}</time>
                    @endif
                </div>
                @if ($item['description'] ?? null)
                    <p class="mt-0.5 text-sm leading-relaxed text-muted">{{ $item['description'] }}</p>
                @endif
            </div>
        </li>
    @endforeach

    @isset($footer)
        <li>{{ $footer }}</li>
    @endisset
</ol>