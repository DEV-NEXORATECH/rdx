@props([
    'name' => null,
    'src' => null,
    'size' => 'md',
    'status' => null, // null | 'online' | 'away' | 'offline'
])

@php
    $sizes = [
        'xs' => 'h-6 w-6 text-[10px]',
        'sm' => 'h-8 w-8 text-xs',
        'md' => 'h-9 w-9 text-sm',
        'lg' => 'h-11 w-11 text-base',
        'xl' => 'h-14 w-14 text-lg',
    ];
@endphp

<span class="relative inline-flex shrink-0" aria-hidden="true">
    @if ($src)
        <img src="{{ $src }}" alt="{{ $name ?? '' }}" @class(['rounded-full object-cover ring-2 ring-white', $sizes[$size]])>
    @else
        <span @class([
            'flex items-center justify-center rounded-full bg-primary-soft font-semibold text-primary',
            $sizes[$size],
        ])>
            {{ str($name ?? '?')->substr(0, 2)->upper() }}
        </span>
    @endif

    @if ($status)
        <span @class([
            'absolute -bottom-0.5 -right-0.5 h-2.5 w-2.5 rounded-full ring-2 ring-white',
            'bg-success' => $status === 'online',
            'bg-warning' => $status === 'away',
            'bg-slate-300' => $status === 'offline',
        ])></span>
    @endif
</span>