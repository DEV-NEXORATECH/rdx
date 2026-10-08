@props(['modalId' => null, 'title' => 'Konfirmasi', 'type' => 'danger', 'confirmText' => 'Ya, lanjutkan'])

@php
    $intent = match ($type) {
        'danger' => ['bg-danger', 'hover:bg-danger/90', 'text-white'],
        'warning' => ['bg-warning', 'hover:bg-warning/90', 'text-white'],
        default => ['bg-primary', 'hover:bg-primary/90', 'text-white'],
    };
@endphp

<div x-data="{ id: '{{ $modalId }}', modalOpen: false }" x-cloak>
    <span @click="modalOpen = true" class="inline-flex">
        {{ $trigger }}
    </span>

    <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-end justify-center p-0 sm:items-center sm:p-4" @keydown.escape.window="modalOpen = false">
        <div x-show="modalOpen" x-transition.opacity.duration.200ms class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="modalOpen = false" aria-hidden="true"></div>

        <div x-show="modalOpen" x-transition x-trap.noscroll="true" class="relative z-10 w-full max-w-md overflow-hidden rounded-2xl bg-surface shadow-xl" role="alertdialog" aria-modal="true">
            <div class="px-5 py-5">
                <div class="flex items-start gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-danger-soft text-danger">
                        <x-icon :name="$type === 'warning' ? 'exclamation-triangle' : 'trash'" class="h-5 w-5" />
                    </span>
                    <div class="min-w-0">
                        <h3 class="text-base font-semibold text-heading">{{ $title }}</h3>
                        <div class="mt-1 text-sm leading-relaxed text-muted">{{ $slot }}</div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse items-stretch justify-end gap-2 border-t border-border bg-slate-50 px-5 py-4 sm:flex-row">
                <button type="button" @click="modalOpen = false" class="focus-ring rounded-lg border border-border bg-surface px-4 py-2 text-sm font-medium text-text transition-colors hover:bg-slate-50">
                    Batal
                </button>
                <button
                    type="button"
                    @click="modalOpen = false; $dispatch('{{ $modalId }}-confirm')"
                    @class(['focus-ring inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition-colors', $intent[0], $intent[1], $intent[2]])
                >
                    <x-icon :name="$type === 'warning' ? 'exclamation-triangle' : 'trash'" class="h-4 w-4" />
                    {{ $confirmText }}
                </button>
            </div>
        </div>
    </div>
</div>