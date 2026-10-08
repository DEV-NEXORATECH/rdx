<div x-data="toastBoard" class="pointer-events-none fixed inset-x-0 top-4 z-[100] flex flex-col items-center gap-2 px-4 sm:items-end sm:px-6">
    @if (session('toast'))
        <div data-flash-toast hidden>@json(session('toast'))</div>
    @endif

    <template x-for="toast in $store.toasts.items" :key="toast.id">
        <div
            x-data="{ visible: false }"
            x-init="$nextTick(() => (visible = true))"
            x-show="visible"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="translate-y-2 opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            class="pointer-events-auto flex w-full max-w-sm items-start gap-3 rounded-xl border bg-surface p-4 shadow-lg"
            :class="$store.toasts.intentClass(toast.type)"
            role="status"
        >
            <template x-if="toast.type === 'success'"><x-icon name="check-circle" class="mt-0.5 h-5 w-5 shrink-0" /></template>
            <template x-if="toast.type === 'error'"><x-icon name="x-circle" class="mt-0.5 h-5 w-5 shrink-0" /></template>
            <template x-if="toast.type === 'warning'"><x-icon name="exclamation-triangle" class="mt-0.5 h-5 w-5 shrink-0" /></template>
            <template x-if="toast.type === 'info'"><x-icon name="information" class="mt-0.5 h-5 w-5 shrink-0" /></template>

            <div class="min-w-0 flex-1">
                <template x-if="toast.title">
                    <p class="text-sm font-semibold leading-tight" x-text="toast.title"></p>
                </template>
                <p class="text-sm leading-snug" x-text="toast.message"></p>
            </div>

            <button type="button" class="shrink-0 rounded p-0.5 opacity-60 hover:opacity-100" @click="$store.toasts.remove(toast.id)" aria-label="Tutup notifikasi">
                <x-icon name="x-mark" class="h-4 w-4" />
            </button>
        </div>
    </template>
</div>