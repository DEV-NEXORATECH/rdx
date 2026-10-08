@props(['items' => []])

<nav aria-label="Breadcrumb" {{ $attributes->class('mb-0 rounded-t-2xl border border-b-0 border-brand-brown bg-brand-brown px-5 pt-3 sm:px-6') }}>
    <ol class="flex flex-wrap items-center gap-1.5 pb-2 text-sm">
        <li>
                <a href="{{ route('dashboard') }}" class="focus-ring flex items-center gap-1 rounded text-white/75 transition-colors hover:text-white">
                <x-icon name="home" class="h-4 w-4" />
                <span class="sr-only">Dashboard</span>
            </a>
        </li>

        @foreach ($items as $index => $item)
            <li class="flex items-center gap-1.5">
                <x-icon name="chevron-right" class="h-4 w-4 text-white/50" />

                @if (is_string($item))
                    <span class="font-medium text-white" aria-current="page">{{ $item }}</span>
                @else
                    @if (isset($item['url']))
                        <a href="{{ $item['url'] }}" class="focus-ring rounded text-white/75 transition-colors hover:text-white">{{ $item['label'] }}</a>
                    @else
                        <span class="font-medium text-white" aria-current="page">{{ $item['label'] }}</span>
                    @endif
                @endif
            </li>
        @endforeach
    </ol>
</nav>
