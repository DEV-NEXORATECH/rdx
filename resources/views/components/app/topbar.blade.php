@props(['active' => null])

@php
    $user = auth()->user();
    $activeRoles = $user?->getRoleNames() ?? collect();
@endphp

<header class="sticky top-0 z-20 border-b border-border bg-surface/95 backdrop-blur">
    <div class="flex h-16 items-center gap-3 px-4 sm:px-6 lg:px-8">
        <button
            type="button"
            class="focus-ring rounded-lg p-2 text-muted hover:bg-slate-50 lg:hidden"
            @click="$store.ui.sidebarOpen = true"
            aria-label="Buka menu"
        >
            <x-icon name="menu" class="h-6 w-6" />
        </button>

        <button
            type="button"
            class="focus-ring hidden h-9 w-9 rounded-lg border border-border bg-surface p-2 text-muted shadow-xs transition-colors hover:bg-primary-soft hover:text-primary lg:flex lg:items-center lg:justify-center"
            @click="$store.ui.toggleCollapsed()"
            :aria-label="$store.ui.sidebarCollapsed ? 'Perbesar sidebar' : 'Ciutkan sidebar'"
            :title="$store.ui.sidebarCollapsed ? 'Perbesar sidebar' : 'Ciutkan sidebar'"
        >
            <x-icon name="chevron-left" class="h-5 w-5 transition-transform" x-bind:class="{ 'rotate-180': $store.ui.sidebarCollapsed }" />
        </button>

        <div class="hidden items-center gap-2 text-sm text-muted md:flex">
            <span>JobFinance</span>
            <x-icon name="chevron-right" class="h-4 w-4" />
            <span class="font-medium text-heading">{{ $active ? ucwords(str_replace(['.', '-', '_'], ' ', $active)) : '' }}</span>
        </div>

        <div class="ml-auto flex items-center gap-2">
            <a href="#" class="focus-ring relative rounded-lg p-2 text-muted hover:bg-slate-50 hover:text-heading" aria-label="Notifikasi">
                <x-icon name="bell" />
            </a>

            <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                <button
                    type="button"
                    class="focus-ring flex items-center gap-2.5 rounded-lg p-1.5 pr-2 hover:bg-slate-50"
                    @click="open = !open"
                    aria-haspopup="menu"
                    :aria-expanded="open"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 text-sm font-bold text-primary-hover">
                        {{ str($user?->name)->substr(0, 1)->upper() }}
                    </span>
                    <span class="hidden text-left sm:block">
                        <span class="block max-w-[10rem] truncate text-sm font-semibold text-heading">{{ $user?->name }}</span>
                        <span class="block text-xs text-muted">{{ $activeRoles->implode(', ') }}</span>
                    </span>
                    <x-icon name="chevron-down" class="h-4 w-4 text-muted" />
                </button>

                <div
                    x-show="open"
                    x-cloak
                    x-transition
                    class="absolute right-0 mt-2 w-56 origin-top-right rounded-xl border border-border bg-surface shadow-lg"
                    role="menu"
                >
                    <div class="border-b border-border px-4 py-3">
                        <p class="text-sm font-semibold text-heading">{{ $user?->email }}</p>
                    </div>
                    <div class="p-1.5">
                        <a href="{{ route('account.profile') }}" class="focus-ring flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-text hover:bg-slate-50" role="menuitem">
                            <x-icon name="user" class="h-4 w-4 text-muted" />
                            Profil Saya
                        </a>
                        <a href="{{ route('account.change-password') }}" class="focus-ring flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-text hover:bg-slate-50" role="menuitem">
                            <x-icon name="key" class="h-4 w-4 text-muted" />
                            Ubah Kata Sandi
                        </a>
                    </div>
                    <div class="border-t border-border p-1.5">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="focus-ring flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-danger hover:bg-danger-soft" role="menuitem">
                                <x-icon name="logout" class="h-4 w-4" />
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
