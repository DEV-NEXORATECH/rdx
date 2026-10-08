<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — ' . config('app.name') : config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ Vite::asset('resources/img/brand-logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="flex h-full flex-col items-center justify-center bg-background px-4 py-12">
    {{ $slot }}
</body>
</html>
