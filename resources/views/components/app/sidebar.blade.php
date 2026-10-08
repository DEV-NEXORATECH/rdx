@props(['active' => null, 'menu' => []])

@php
    $menu = $menu ?: \App\Domains\Admin\Support\MenuDefinition::items();
@endphp

<aside
    x-data
    class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full border-r border-border bg-surface transition-all duration-200 lg:translate-x-0"
    :class="{
        'translate-x-0': $store.ui.sidebarOpen,
        '-translate-x-full': ! $store.ui.sidebarOpen,
        'lg:w-20': $store.ui.sidebarCollapsed,
        'lg:w-72': ! $store.ui.sidebarCollapsed,
    }"
    @keydown.escape.window="$store.ui.sidebarOpen = false"
>
    <div class="flex h-full flex-col">
        <div class="flex h-16 shrink-0 items-center justify-between border-b border-border px-4">
            <x-app.brand />

            <button type="button" class="focus-ring rounded-lg p-1.5 text-muted hover:bg-slate-50 lg:hidden" @click="$store.ui.sidebarOpen = false" aria-label="Tutup menu">
                <x-icon name="x-mark" />
            </button>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4" aria-label="Navigasi utama">
            @foreach ($menu as $item)
                @if (isset($item['children']))
                    <x-app.nav-group :item="$item" :active="$active" />
                @else
                    <x-app.nav-link :item="$item" :active="$active" />
                @endif
            @endforeach
        </nav>

    </div>
</aside>

<template x-teleport="body">
    <div
        x-show="$store.ui.sidebarOpen"
        x-cloak
        x-transition.opacity
        class="fixed inset-0 z-30 bg-slate-900/40 backdrop-blur-sm lg:hidden"
        @click="$store.ui.sidebarOpen = false"
        aria-hidden="true"
    ></div>
</template>
