@props([
    'columns' => [],
    'rows' => [],
    'rowKey' => 'id',
    'rowActions' => null,
    'loading' => false,
    'sort' => null,
    'direction' => 'asc',
    'striped' => false,
    'nowrap' => false,
    'emptyIcon' => 'document-text',
    'emptyTitle' => 'Belum ada data',
    'emptyDescription' => 'Data akan tampil di sini setelah tersedia.',
])

@php
    $paginatedRows = $rows instanceof \Illuminate\Contracts\Pagination\Paginator ? $rows : null;
    $items = $rows instanceof \Illuminate\Support\Collection || is_array($rows)
        ? collect($rows)
        : ($rows instanceof \Illuminate\Contracts\Pagination\Paginator ? $rows->items() : collect($rows));
    $items = collect($items);
@endphp

<div class="overflow-hidden rounded-xl border border-border bg-surface shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-border text-sm">
            <thead class="bg-slate-50">
                <tr>
                    @foreach ($columns as $column)
                        @php
                            $align = $column['align'] ?? 'left';
                            $sortKey = $column['sort'] ?? (($column['sortable'] ?? false) ? $column['key'] : null);
                        @endphp
                        <th scope="col" @class([
                            'px-4 py-3 text-left font-semibold text-heading',
                            'whitespace-nowrap' => $nowrap || ($column['nowrap'] ?? false),
                            $align === 'right' ? 'text-right' : '',
                            $align === 'center' ? 'text-center' : '',
                        ]) style="{{ isset($column['width']) ? 'width:' . $column['width'] : '' }}">
                            @if ($sortKey)
                                <button type="button" wire:click="sortBy('{{ $sortKey }}')" class="focus-ring group inline-flex items-center gap-1 rounded uppercase text-[11px] tracking-wide text-heading hover:text-primary">
                                    {{ $column['label'] }}
                                    <span class="text-muted">
                                        @if ($sort === $sortKey)
                                            <x-icon :name="$direction === 'asc' ? 'chevron-up' : 'chevron-down'" class="h-3.5 w-3.5 text-primary" />
                                        @else
                                            <x-icon name="chevron-up-down" class="h-3.5 w-3.5 opacity-50" />
                                        @endif
                                    </span>
                                </button>
                            @else
                                <span @class(['uppercase text-[11px] tracking-wide' => true])>{{ $column['label'] }}</span>
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody class="divide-y divide-border bg-surface">
                @if ($loading)
                    @for ($i = 0; $i < 5; $i++)
                        <tr>
                            @foreach ($columns as $column)
                                <td class="px-4 py-3">
                                    <div class="h-4 w-full animate-pulse rounded bg-slate-100 {{ isset($column['width']) ? '' : 'max-w-[10rem]' }}"></div>
                                </td>
                            @endforeach
                        </tr>
                    @endfor
                @elseif ($items->isEmpty())
                    <tr>
                        <td colspan="{{ count($columns) }}" class="px-4 py-12">
                            <x-empty-state
                                :icon="$emptyIcon ?? 'document-text'"
                                :title="$emptyTitle ?? 'Belum ada data'"
                                :description="$emptyDescription ?? 'Data akan tampil di sini setelah tersedia.'"
                            />
                        </td>
                    </tr>
                @else
                    @foreach ($items as $row)
                        <tr @class([
                            'transition-colors hover:bg-slate-50/70',
                            'bg-slate-50/40' => $striped && $loop->even,
                        ])>
                            @foreach ($columns as $column)
                                @php
                                    $align = $column['align'] ?? 'left';
                                    $type = $column['type'] ?? 'text';
                                    $value = data_get($row, $column['key']);
                                @endphp
                                <td @class([
                                    'px-4 py-3 text-text',
                                    'whitespace-nowrap' => $nowrap || ($column['nowrap'] ?? false),
                                    $align === 'right' ? 'text-right tabular-nums' : '',
                                    $align === 'center' ? 'text-center' : '',
                                ])>
                                    @if ($type === 'actions' && $rowActions)
                                        @php
                                            $actions = $rowActions($row);
                                        @endphp
                                        @if (! empty($actions))
                                            <x-table.row-actions :actions="$actions" :align="$align" />
                                        @endif
                                    @elseif ($type === 'money')
                                        <span class="font-medium tabular-nums">{{ \App\Domains\Shared\Support\Money::format($value) }}</span>
                                    @elseif ($type === 'number')
                                        <span class="tabular-nums">{{ $value === null || $value === '' ? '—' : \Illuminate\Support\Number::format((float) $value, 0, locale: 'id') }}</span>
                                    @elseif ($type === 'date')
                                        @if ($value)
                                            <span class="tabular-nums">{{ \Illuminate\Support\Carbon::parse($value)->translatedFormat('d M Y') }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    @elseif ($type === 'datetime')
                                        @if ($value)
                                            <span class="tabular-nums">{{ \Illuminate\Support\Carbon::parse($value)->translatedFormat('d M Y H:i') }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    @elseif ($type === 'badge')
                                        @php
                                            $map = $column['map'] ?? [];
                                            $defaultIntent = $column['defaultIntent'] ?? 'neutral';
                                            $entry = $map[$value] ?? ['label' => $value, 'intent' => $defaultIntent];
                                            $entry = is_string($entry) ? ['label' => $entry, 'intent' => $defaultIntent] : $entry;
                                        @endphp
                                        <x-status-badge :intent="$entry['intent'] ?? $defaultIntent">
                                            {{ $entry['label'] ?? $value }}
                                        </x-status-badge>
                                    @elseif ($type === 'boolean')
                                        @if ($value)
                                            <x-icon name="check-circle" class="h-5 w-5 text-success" />
                                        @else
                                            <x-icon name="x-circle" class="h-5 w-5 text-slate-300" />
                                        @endif
                                    @elseif ($type === 'html')
                                        {!! $value !!}
                                    @elseif (isset($column['render']))
                                        {{ $column['render']($row, $loop->parent->index) }}
                                    @else
                                        @if ($value === null || $value === '')
                                            <span class="text-muted">—</span>
                                        @else
                                            {{ $value }}
                                        @endif
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    @if ($paginatedRows && $paginatedRows->hasPages())
        <div class="border-t border-border px-5 py-3">
            <x-pagination :paginator="$paginatedRows" {{ $attributes->only('wire:key') }} />
        </div>
    @endif
</div>