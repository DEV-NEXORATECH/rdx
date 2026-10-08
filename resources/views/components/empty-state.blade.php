@props(['title' => 'Belum ada data', 'description' => null, 'icon' => 'document-text'])

<div class="flex flex-col items-center justify-center px-4 py-10 text-center">
    <span class="flex h-14 w-14 items-center justify-center rounded-full bg-primary-soft text-primary">
        <x-icon :name="$icon" class="h-7 w-7" />
    </span>
    <h3 class="mt-4 text-base font-semibold text-heading">{{ $title }}</h3>
    @if ($description)
        <p class="mt-1 max-w-sm text-sm text-muted">{{ $description }}</p>
    @endif
    @if (isset($action) && trim((string) $action))
        <div class="mt-4">{{ $action }}</div>
    @endif
</div>