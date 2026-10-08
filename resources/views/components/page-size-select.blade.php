@props(['label' => 'Baris per halaman', 'options' => [10, 15, 25, 50, 100]])

<label class="flex items-center gap-2 text-sm text-muted">
    <span class="sr-only">{{ $label }}</span>
    <span>{{ $label }}</span>
    <select {{ $attributes->merge(['class' => 'focus-ring input-base w-auto rounded-lg py-1.5 pr-8']) }}>
        @foreach ($options as $option)
            <option value="{{ $option }}">{{ $option }}</option>
        @endforeach
    </select>
</label>