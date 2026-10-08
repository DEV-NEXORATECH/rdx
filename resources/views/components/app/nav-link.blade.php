@props(['item' => [], 'active' => null])

@php
    $isActive = isset($item['active'])
        ? str($active)->is($item['active'])
        : ($item['route'] ?? null) === $active;
@endphp

<a
    href="{{ isset($item['route']) ? route($item['route']) : '#' }}"
    @class([
        'group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors',
        'bg-primary-soft text-primary-hover' => $isActive,
        'text-text hover:bg-slate-50 hover:text-heading' => ! $isActive,
    ])
    :class="$store.ui.sidebarCollapsed ? 'justify-center px-2' : ''"
    aria-current="{{ $isActive ? 'page' : false }}"
>
    <x-icon :name="$item['icon'] ?? 'document-text'" class="h-5 w-5" />
    <span x-show="!$store.ui.sidebarCollapsed" x-cloak class="flex-1">{{ $item['label'] }}</span>
    @if ($isActive)
        <span x-show="!$store.ui.sidebarCollapsed" x-cloak class="h-1.5 w-1.5 rounded-full bg-primary"></span>
    @endif
</a>
