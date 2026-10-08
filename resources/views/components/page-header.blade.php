@props(['title' => '', 'description' => null, 'eyebrow' => null, 'icon' => null])

@php
    $routeName = request()->route()?->getName() ?? '';
    $icon ??= match (true) {
        str_contains($routeName, 'dashboard') => 'home',
        str_contains($routeName, 'quotations') => 'document-text',
        str_contains($routeName, 'tariffs') => 'tag',
        str_contains($routeName, 'jobs') => 'truck',
        str_contains($routeName, 'job-costs') => 'calculator',
        str_contains($routeName, 'invoices') => 'receipt',
        str_contains($routeName, 'reports') => 'chart-bar',
        str_contains($routeName, 'accounts') => 'banknotes',
        str_contains($routeName, 'periods') => 'calendar',
        str_contains($routeName, 'journals') => 'document-text',
        str_contains($routeName, 'audits') => 'shield-check',
        str_contains($routeName, 'users') => 'user-group',
        str_contains($routeName, 'roles') => 'key',
        str_contains($routeName, 'permissions') => 'shield-check',
        str_contains($routeName, 'profile') => 'user',
        str_contains($routeName, 'master.') || str_contains($routeName, 'crm.') || str_contains($routeName, 'fleet.') => 'building',
        default => 'sparkles',
    };
@endphp

<div {{ $attributes->class('flex flex-col gap-3 rounded-b-2xl border border-t-0 border-b-4 border-brand-brown border-b-primary bg-brand-brown px-5 pb-6 pt-0 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:px-6') }}>
    <div class="flex min-w-0 items-start gap-3">
        <span class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary text-white shadow-sm ring-4 ring-white/10">
            <x-icon :name="$icon" class="h-5 w-5" />
        </span>
        <div class="min-w-0">
            @if ($eyebrow ?? null)
                <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-white/75">{{ $eyebrow }}</p>
            @endif
            <h1 class="text-2xl font-bold tracking-tight text-white">{{ $title }}</h1>
            @if ($description)
                <p class="mt-1.5 max-w-2xl text-sm leading-relaxed text-white/80">{{ $description }}</p>
            @endif
            {{ $slot }}
        </div>
    </div>

    @if (isset($actions) && trim((string) $actions))
        <div class="flex flex-wrap items-center gap-2">
            {{ $actions }}
        </div>
    @endif
</div>
