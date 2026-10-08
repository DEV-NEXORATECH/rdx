<div>
    <x-breadcrumbs :items="['Pustaka Komponen']" />
    <x-page-header title="Pustaka Komponen" description="Referensi cepat komponen UI JobFinance (token, tombol, badge, form, tabel, modal, dll)." />

    <div class="mt-6 space-y-6">
        <x-content-card title="Token Warna" padding>
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                <div class="flex items-center gap-3 rounded-lg border border-border p-3">
                    <span class="h-9 w-9 rounded-lg bg-background"></span>
                    <div>
                        <p class="text-sm font-medium text-heading">Background</p>
                        <p class="text-xs text-muted">#F8FAFC</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 rounded-lg border border-border p-3">
                    <span class="h-9 w-9 rounded-lg bg-surface"></span>
                    <div>
                        <p class="text-sm font-medium text-heading">Surface</p>
                        <p class="text-xs text-muted">#FFFFFF</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 rounded-lg border border-border p-3">
                    <span class="h-9 w-9 rounded-lg bg-primary"></span>
                    <div>
                        <p class="text-sm font-medium text-heading">Primary</p>
                        <p class="text-xs text-muted">#2563EB</p>
                    </div>
                </div>
            </div>
        </x-content-card>

        <x-content-card title="Tombol" padding>
            <div class="flex flex-wrap gap-2">
                <x-button variant="primary" icon="plus">Utama</x-button>
                <x-button variant="secondary" icon="pencil">Sekunder</x-button>
                <x-button variant="ghost">Ghost</x-button>
                <x-button variant="danger" icon="trash">Berbahaya</x-button>
                <x-button variant="icon" icon="cog" aria-label="Pengaturan" />
            </div>
        </x-content-card>

        <x-content-card title="Badge & Status" padding>
            <div class="flex flex-wrap gap-2">
                <x-badge>Default</x-badge>
                <x-badge intent="primary">Primary</x-badge>
                <x-badge intent="success">Sukses</x-badge>
                <x-badge intent="warning">Peringatan</x-badge>
                <x-badge intent="danger">Bahaya</x-badge>
                <x-status-badge intent="success">Aktif</x-status-badge>
                <x-status-badge intent="neutral">Menunggu</x-status-badge>
                <x-status-badge intent="danger">Ditolak</x-status-badge>
            </div>
        </x-content-card>
    </div>
</div>