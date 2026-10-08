<div>
    <x-breadcrumbs :items="[$meta['title']]" />
    <x-page-header :title="$meta['title']" description="Kelola data master yang digunakan pada proses order." />

    @if (session('status'))
        <div class="mt-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('status') }}</div>
    @endif

    <div class="mt-6 grid gap-6 lg:grid-cols-[minmax(0,1fr)_22rem]">
        <x-content-card :padding="false" overflow-visible>
            <div class="flex flex-col gap-3 border-b border-border p-5 sm:flex-row sm:items-center sm:justify-between">
                <input wire:model.live="search" type="search" placeholder="Cari data..." class="w-full sm:max-w-xs">
                <x-button :href="route(str_replace('.index', '.create', request()->route()->getName()))" variant="primary" icon="plus">Tambah Data</x-button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-border text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-muted">
                        <tr>
                            <th class="px-5 py-3"><button wire:click="sortBy('code')">Kode ↕</button></th>
                            <th class="px-5 py-3"><button wire:click="sortBy('name')">Nama ↕</button></th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse ($records as $record)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-3 text-muted">{{ $record->code ?: '-' }}</td>
                                <td class="px-5 py-3 font-medium text-heading">{{ $record->name }}</td>
                                <td class="px-5 py-3"><x-badge :intent="$record->is_active ? 'success' : 'neutral'">{{ $record->is_active ? 'Aktif' : 'Nonaktif' }}</x-badge></td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route(str_replace('.index', '.edit', request()->route()->getName()), ['record' => $record->id]) }}" class="mr-3 text-primary hover:underline">Edit</a>
                                    <button wire:click="delete({{ $record->id }})" wire:confirm="Hapus data ini?" class="text-danger hover:underline">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-5 py-10 text-center text-muted">Belum ada data {{ strtolower($meta['singular']) }}.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-border px-5 py-3">{{ $records->links() }}</div>
        </x-content-card>

    </div>
</div>
