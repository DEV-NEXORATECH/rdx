<a href="{{ route('dashboard') }}" {{ $attributes->merge(['class' => 'group flex w-full items-center justify-center px-2']) }} wire:navigate>
    <span class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-full bg-white shadow-sm ring-1 ring-border">
        <img src="{{ Vite::asset('resources/img/brand-logo.png') }}" alt="Logo JobFinance" class="h-full w-full object-cover">
    </span>
</a>
