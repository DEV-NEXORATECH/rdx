@props(['title' => null, 'description' => null, 'leading' => null])

<section {{ $attributes->merge(['class' => 'space-y-4']) }}>
    @if ($title || $description)
        <div class="flex items-start gap-3">
            @if ($leading ?? null)
                <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-primary-soft text-primary">
                    {{ $leading }}
                </span>
            @endif
            <div>
                @if ($title)
                    <h3 class="text-base font-semibold text-heading">{{ $title }}</h3>
                @endif
                @if ($description)
                    <p class="mt-0.5 text-sm text-muted">{{ $description }}</p>
                @endif
            </div>
        </div>
    @endif

    {{ $slot }}
</section>