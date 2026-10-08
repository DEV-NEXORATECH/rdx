@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'icon' => null,
    'iconPosition' => 'left',
    'loading' => false,
])

@php
    $variants = [
        'primary' => 'bg-primary text-white shadow-xs hover:bg-primary-hover focus-visible:ring-primary/60',
        'secondary' => 'border border-border bg-surface text-text hover:bg-slate-50 focus-visible:ring-slate-300',
        'soft' => 'bg-primary-soft text-primary-hover hover:bg-primary-100 focus-visible:ring-primary/40',
        'danger' => 'bg-danger text-white shadow-xs hover:bg-red-700 focus-visible:ring-danger/50',
        'danger-soft' => 'bg-danger-soft text-danger hover:bg-red-100 focus-visible:ring-danger/40',
        'ghost' => 'text-text hover:bg-slate-100 hover:text-heading focus-visible:ring-slate-300',
        'icon' => 'bg-transparent text-muted hover:bg-slate-100 hover:text-heading focus-visible:ring-slate-300',
    ];

    $sizes = [
        'sm' => 'gap-1.5 px-2.5 py-1.5 text-xs',
        'md' => 'gap-2 px-3.5 py-2 text-sm',
        'lg' => 'gap-2 px-5 py-2.5 text-sm',
    ];

    $classes = [
        'focus-ring inline-flex shrink-0 items-center justify-center rounded-lg font-semibold transition-colors disabled:pointer-events-none disabled:opacity-60',
        $variants[$variant],
        $sizes[$size],
    ];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->except('href')->class($classes) }}>
        @if ($icon && $iconPosition === 'left')
            <x-icon :name="$icon" class="h-4 w-4" />
        @endif
        {{ $slot }}
        @if ($icon && $iconPosition === 'right')
            <x-icon :name="$icon" class="h-4 w-4" />
        @endif
    </a>
@else
    <button {{ $attributes->merge(['type' => 'button'])->class($classes) }}>
        @if ($loading)
            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
        @elseif ($icon && $iconPosition === 'left')
            <x-icon :name="$icon" class="h-4 w-4" />
        @endif

        {{ $slot }}

        @if ($icon && $iconPosition === 'right' && ! $loading)
            <x-icon :name="$icon" class="h-4 w-4" />
        @endif
    </button>
@endif
