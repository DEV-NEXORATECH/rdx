@props(['name' => null, 'accept' => null, 'multiple' => false, 'hint' => null])

<label
    {{ $attributes->whereDoesntStartWith('wire:')->merge(['class' => 'focus-ring flex cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-border bg-slate-50 px-4 py-6 text-center transition-colors hover:border-primary hover:bg-primary-soft/50']) }}
>
    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-soft text-primary">
        <x-icon name="arrow-up-tray" class="h-5 w-5" />
    </span>
    <span class="text-sm font-medium text-heading">Pilih berkas{{ $multiple ? ' (multiple)' : '' }}</span>
    @if ($hint)
        <span class="text-xs text-muted">{{ $hint }}</span>
    @endif
    <input
        @if ($name) name="{{ $name }}" @endif
        type="file"
        class="hidden"
        @if ($accept) accept="{{ $accept }}" @endif
        @if ($multiple) multiple @endif
        {{ $attributes->whereStartsWith('wire:') }}
    >
</label>