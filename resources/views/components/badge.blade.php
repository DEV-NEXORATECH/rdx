@props([
    'intent' => 'neutral',
    'dot' => false,
    'size' => 'sm',
])

@php
    $intents = [
        'neutral' => 'bg-slate-100 text-slate-700 ring-slate-200',
        'primary' => 'bg-primary-soft text-primary-hover ring-primary-200',
        'success' => 'bg-success-soft text-success ring-success/20',
        'warning' => 'bg-warning-soft text-warning ring-warning/20',
        'danger' => 'bg-danger-soft text-danger ring-danger/20',
        'muted' => 'bg-slate-50 text-muted ring-slate-200',
        'info' => 'bg-primary-50 text-primary-700 ring-primary-200',
    ];

    $dotColors = [
        'neutral' => 'bg-slate-400',
        'primary' => 'bg-primary',
        'success' => 'bg-success',
        'warning' => 'bg-warning',
        'danger' => 'bg-danger',
        'muted' => 'bg-slate-300',
        'info' => 'bg-primary-500',
    ];

    $sizes = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-1 text-sm',
    ];
@endphp

<span
    {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-full font-medium ring-1 ring-inset ' . $intents[$intent] . ' ' . $sizes[$size]]) }}
>
    @if ($dot)
        <span @class(['h-1.5 w-1.5 rounded-full', $dotColors[$intent]])></span>
    @endif
    {{ $slot }}
</span>