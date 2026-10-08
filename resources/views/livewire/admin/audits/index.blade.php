<div>
    <x-breadcrumbs :items="['Administrasi', 'Jejak Audit']" />
    <x-page-header title="Jejak Audit" description="Catatan perubahan sistem yang bersifat kritikal." />

    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <input wire:model.live.debounce.300ms="search" type="search" placeholder="Cari sumber atau referensi..." class="w-full sm:max-w-sm">
        <select wire:model.live="action" class="w-full sm:max-w-xs">
            <option value="">Semua aksi</option>
            <option value="created">Dibuat</option>
            <option value="updated">Diubah</option>
            <option value="deleted">Dihapus</option>
            <option value="reversed">Dibalik</option>
            <option value="reversal">Jurnal pembalik</option>
        </select>
    </div>

    <x-content-card class="mt-4" :padding="false">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase text-muted">
                    <tr>
                        <th class="px-5 py-3">Waktu</th>
                        <th class="px-5 py-3">Aksi</th>
                        <th class="px-5 py-3">Sumber</th>
                        <th class="px-5 py-3">Referensi</th>
                        <th class="px-5 py-3">Deskripsi</th>
                        <th class="px-5 py-3">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($audits as $audit)
                        @php($journal = $audit->journalEntry)
                        @php($snapshot = $audit->snapshot ?? [])
                        <tr>
                            <td class="whitespace-nowrap px-5 py-3 text-muted">{{ $audit->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-3">
                                <x-badge>{{ match ($audit->action) { 'created' => 'Dibuat', 'updated' => 'Diubah', 'deleted' => 'Dihapus', 'reversed' => 'Dibalik', 'reversal' => 'Pembalik', default => ucfirst($audit->action) } }}</x-badge>
                            </td>
                            <td class="px-5 py-3">{{ $audit->source_type ? ucfirst(str_replace('_', ' ', $audit->source_type)).' #'.$audit->source_id : 'Jurnal' }}</td>
                            <td class="px-5 py-3 font-medium">{{ $journal?->reference ?? ($snapshot['reference'] ?? '-') }}</td>
                            <td class="px-5 py-3">{{ $journal?->description ?? ($snapshot['description'] ?? '-') }}</td>
                            <td class="px-5 py-3">{{ isset($journal?->amount) ? \App\Domains\Shared\Support\Money::format($journal->amount) : (isset($snapshot['amount']) ? \App\Domains\Shared\Support\Money::format($snapshot['amount']) : '-') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center text-muted">Belum ada histori audit jurnal.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-5">{{ $audits->links() }}</div>
    </x-content-card>
</div>
