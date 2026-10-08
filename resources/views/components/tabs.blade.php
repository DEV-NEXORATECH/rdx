@props([
    'items' => [],           // [{ key, label, icon, href?, active? }]
    'activeKey' => null,
])

<div {{ $attributes->class('border-b border-border') }}>
    <nav class="-mb-px flex flex-wrap gap-1" aria-label="Tabs">
        @foreach ($items as $tab)
            @php
                $active = $tab['active'] ?? ($activeKey !== null && $tab['key'] === $activeKey);
            @endphp

            @if (isset($tab['href']))
                <a
                    href="{{ $tab['href'] }}"
                    @class([
                        'focus-ring inline-flex items-center gap-2 rounded-t-lg border-b-2 px-3 py-2.5 text-sm font-medium transition-colors',
                        'border-primary text-primary' => $active,
                        'border-transparent text-muted hover:border-border hover:text-heading' => ! $active,
                    ])
                    aria-current="{{ $active ? 'page' : 'false' }}"
                >
                    @if ($tab['icon'] ?? null)
                        <x-icon :name="$tab['icon']" class="h-4 w-4" />
                    @endif
                    {{ $tab['label'] }}
                </a>
            @else
                <button
                    type="button"
                    @if (isset($tab['wireClick'])) wire:click="{{ $tab['wireClick'] }}" @endif
                    @class([
                        'focus-ring inline-flex items-center gap-2 rounded-t-lg border-b-2 px-3 py-2.5 text-sm font-medium transition-colors',
                        'border-primary text-primary' => $active,
                        'border-transparent text-muted hover:border-border hover:text-heading' => ! $active,
                    ])
                    aria-selected="{{ $active ? 'true' : 'false' }}"
                    role="tab"
                >
                    @if ($tab['icon'] ?? null)
                        <x-icon :name="$tab['icon']" class="h-4 w-4" />
                    @endif
                    {{ $tab['label'] }}
                </button>
            @endif
        @endforeach
    </nav>
</div>