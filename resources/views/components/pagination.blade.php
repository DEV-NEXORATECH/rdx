@props(['paginator' => null])

@if ($paginator && $paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman" class="flex flex-col items-center justify-between gap-4 sm:flex-row">
        <p class="text-sm text-muted">
            Menampilkan
            <span class="font-semibold text-heading">{{ $paginator->firstItem() }}</span>–<span class="font-semibold text-text">{{ $paginator->lastItem() }}</span>
            dari
            <span class="font-semibold text-heading">{{ $paginator->total() }}</span>
            entitas
        </p>

        <div class="flex items-center gap-1">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="flex h-9 w-9 cursor-not-allowed items-center justify-center rounded-lg border border-border bg-slate-50 text-muted opacity-60">
                    <x-icon name="chevron-left" class="h-4 w-4" />
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="focus-ring flex h-9 w-9 items-center justify-center rounded-lg border border-border bg-surface text-text transition-colors hover:border-primary hover:text-primary" rel="prev" aria-label="Halaman sebelumnya">
                    <x-icon name="chevron-left" class="h-4 w-4" />
                </a>
            @endif

            {{-- Page numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-2 text-sm text-muted" aria-disabled="true">{{ $element }}</span>
                @elseif (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="flex h-9 min-w-9 items-center justify-center rounded-lg bg-primary px-3 text-sm font-semibold text-white shadow-xs">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="focus-ring flex h-9 min-w-9 items-center justify-center rounded-lg border border-border bg-surface px-3 text-sm text-text transition-colors hover:border-primary hover:bg-primary-soft hover:text-primary" aria-label="Go to page {{ $page }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="focus-ring flex h-9 w-9 items-center justify-center rounded-lg border border-border bg-surface text-text transition-colors hover:border-primary hover:text-primary" rel="next" aria-label="Halaman berikutnya">
                    <x-icon name="chevron-right" class="h-4 w-4" />
                </a>
            @else
                <span class="flex h-9 w-9 cursor-not-allowed items-center justify-center rounded-lg border border-border bg-slate-50 text-muted opacity-60">
                    <x-icon name="chevron-right" class="h-4 w-4" />
                </span>
            @endif
        </div>
    </nav>
@endif