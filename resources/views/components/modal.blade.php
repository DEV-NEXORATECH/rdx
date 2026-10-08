@props(['show' => false, 'title' => null, 'maxWidth' => 'lg', 'name' => 'modal', 'closeable' => true])

@php
    $widths = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
        '3xl' => 'sm:max-w-3xl',
        '4xl' => 'sm:max-w-4xl',
    ];
@endphp

<div
    x-data="{ show: @js($show) }"
    x-init="() => $watch('show', v => document.body.classList.toggle('overflow-hidden', v))"
    x-on:open-modal.window="if ($event.detail === '{{ $name }}') show = true"
    x-on:close-modal.window="if ($event.detail === '{{ $name }}') show = false"
    @keydown.escape.window="if ({{ $closeable ? 'true' : 'false' }}) show = false"
    class="relative z-60"
>
    <div x-show="show" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-0 sm:items-center sm:p-4">
            <div
                x-show="show"
                x-cloak
                x-transition.opacity.duration.200ms
                class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm"
                @if ($closeable) @click="show = false" @endif
                aria-hidden="true"
            ></div>

            <div
                x-show="show"
                x-cloak
                x-transition
                x-trap.noscroll="{{ $closeable ? 'true' : 'false' }}"
                class="relative z-10 w-full overflow-hidden rounded-t-2xl bg-surface shadow-xl sm:rounded-2xl {{ $widths[$maxWidth] }}"
                role="dialog"
                aria-modal="true"
                @if ($title) aria-labelledby="modal-{{ $name }}-title" @endif
            >
                <div class="flex items-start justify-between gap-4 border-b border-border px-5 py-4">
                    <div class="min-w-0">
                        @if ($title)
                            <h2 id="modal-{{ $name }}-title" class="text-base font-semibold text-heading">{{ $title }}</h2>
                        @endif
                        @isset($description)
                            <p class="mt-0.5 text-sm text-muted">{{ $description }}</p>
                        @endisset
                        {{ $header ?? '' }}
                    </div>

                    @if ($closeable)
                        <button type="button" class="focus-ring shrink-0 rounded-lg p-1.5 text-muted transition-colors hover:bg-slate-100 hover:text-heading" @click="show = false" aria-label="Tutup">
                            <x-icon name="x-mark" />
                        </button>
                    @endif
                </div>

                <div class="px-5 py-5">
                    {{ $slot }}
                </div>

                @if (isset($footer) && trim((string) $footer))
                    <div class="flex flex-col-reverse items-stretch justify-end gap-2 border-t border-border bg-slate-50 px-5 py-4 sm:flex-row sm:items-center sm:justify-end">
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>