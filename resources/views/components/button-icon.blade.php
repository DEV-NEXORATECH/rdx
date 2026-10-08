@props(['label' => null, 'loading' => false])

<button
    {{ $attributes->merge(['type' => 'button'])->class('focus-ring inline-flex shrink-0 items-center justify-center rounded-lg p-2 text-muted transition-colors hover:bg-slate-100 hover:text-heading disabled:pointer-events-none disabled:opacity-50') }}
    @if ($label) aria-label="{{ $label }}" @endif
>
    @if ($loading)
        <svg class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
        </svg>
    @else
        {{ $slot }}
    @endif
</button>