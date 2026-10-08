@php
    $active = request()->route()?->getName();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — ' . config('app.name') : config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ Vite::asset('resources/img/brand-logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body x-data class="h-full bg-background">
    <div class="min-h-full transition-[padding] duration-200" :class="$store.ui.sidebarCollapsed ? 'lg:pl-20' : 'lg:pl-72'">
        <x-app.sidebar :active="$active" />

        <div class="flex min-h-screen flex-col">
            <x-app.topbar :active="$active" />

            <main class="mx-auto w-full max-w-7xl flex-1 px-4 py-6 sm:px-6 lg:px-8">
                {{ $slot }}
            </main>

        </div>
    </div>

    <x-toast />

    @livewireScripts
</body>
</html>
