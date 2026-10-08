@props(['label' => null])

<div {{ $attributes->merge(['class' => 'animate-pulse space-y-3']) }}>
    @if ($label)
        <div class="h-3 w-32 rounded bg-slate-200"></div>
    @endif
    <div class="h-4 w-full rounded bg-slate-100"></div>
    <div class="h-4 w-4/5 rounded bg-slate-100"></div>
    <div class="h-4 w-3/5 rounded bg-slate-100"></div>
</div>