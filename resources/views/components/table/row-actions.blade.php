@props(['actions' => [], 'align' => 'right'])

<div @class(['flex items-center gap-0.5', $align === 'right' ? 'justify-end' : '', $align === 'center' ? 'justify-center' : ''])>
    @foreach ($actions as $action)
        @php
            $permission = $action['permission'] ?? null;
            $isAllowed = $permission === null || auth()->user()?->can($permission);
        @endphp

        @if ($isAllowed)
            @php
                $intent = $action['intent'] ?? 'secondary';
                $icon = $action['icon'] ?? null;
                $label = $action['label'] ?? '';

                $buttonClasses = match ($intent) {
                    'primary' => 'text-primary hover:bg-primary-soft',
                    'danger' => 'text-danger hover:bg-danger-soft',
                    default => 'text-muted hover:bg-slate-100 hover:text-heading',
                };
            @endphp

            @if (isset($action['href']))
                <a
                    href="{{ $action['href'] }}"
                    @class(['focus-ring inline-flex items-center gap-1.5 rounded-lg p-2 text-sm font-medium transition-colors', $buttonClasses])
                    @if ($action['tooltip'] ?? null) title="{{ $action['tooltip'] }}" @endif
                    @if ($action['external'] ?? false) target="_blank" rel="noopener noreferrer" @endif
                >
                    @if ($icon)
                        <x-icon :name="$icon" class="h-4 w-4" />
                    @endif
                    @if ($label && ($action['showLabel'] ?? false))
                        <span>{{ $label }}</span>
                    @endif
                </a>
            @else
                <button
                    type="button"
                    @if (isset($action['wireClick'])) wire:click="{{ $action['wireClick'] }}" @endif
                    @if (isset($action['confirm'])) wire:confirm="{{ $action['confirm'] }}" @endif
                    @class(['focus-ring inline-flex items-center gap-1.5 rounded-lg p-2 text-sm font-medium transition-colors', $buttonClasses])
                    @if ($action['tooltip'] ?? null) title="{{ $action['tooltip'] }}" @endif
                >
                    @if ($icon)
                        <x-icon :name="$icon" class="h-4 w-4" />
                    @endif
                    @if ($label && ($action['showLabel'] ?? false))
                        <span>{{ $label }}</span>
                    @endif
                </button>
            @endif
        @endif
    @endforeach
</div>