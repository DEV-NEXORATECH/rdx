@props(['item' => [], 'active' => null])

@php
    $hasActiveChild = collect($item['children'] ?? [])->contains(fn ($child) => isset($child['active'])
        ? str($active)->is($child['active'])
        : ($child['route'] ?? null) === $active);
@endphp

<div x-data="{ open: @json($hasActiveChild) }" class="space-y-1">
    <button
        type="button"
        class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-text transition-colors hover:bg-slate-50 hover:text-heading"
        @click="$store.ui.sidebarCollapsed ? ($store.ui.sidebarCollapsed = false) : open = !open"
        :aria-expanded="open"
        :class="$store.ui.sidebarCollapsed ? 'justify-center px-2' : ''"
    >
        <x-icon :name="$item['icon'] ?? 'folder'" class="h-5 w-5" />
        <span x-show="!$store.ui.sidebarCollapsed" x-cloak class="flex-1 text-left">{{ $item['label'] }}</span>
        <x-icon x-show="!$store.ui.sidebarCollapsed" x-cloak name="chevron-down" class="h-4 w-4 text-muted transition-transform" x-bind:class="{ 'rotate-180': open }" />
    </button>

    <div x-show="open" x-cloak x-collapse>
        <div class="ml-4 space-y-1 border-l border-border pl-3">
            @foreach ($item['children'] as $child)
                <x-app.nav-link :item="$child" :active="$active" />
            @endforeach
        </div>
    </div>
</div>
