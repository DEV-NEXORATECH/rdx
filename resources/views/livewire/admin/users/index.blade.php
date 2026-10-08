<div>
    <x-breadcrumbs :items="['Administrasi', 'Pengguna']" />
    <x-page-header title="Manajemen Pengguna" description="Kelola akses pengguna dan peran mereka.">
        <x-slot name="actions">
            @can('user.create')
                <x-button variant="primary" icon="user-plus" href="{{ route('admin.users.create') }}">
                    Tambah Pengguna
                </x-button>
            @endcan
        </x-slot>
    </x-page-header>

    <x-content-card class="mt-6">
        <x-table.toolbar>
            <x-slot name="left">
                <x-search-input wire:model.live.debounce.300ms="search" />
                <x-form.select
                    wire:model.live="role"
                    :options="array_merge(['' => 'Semua Peran'], $roles->mapWithKeys(fn ($role) => [$role => $role])->all())"
                    class="w-full sm:w-48"
                    allow-empty="true"
                />
                <x-form.select
                    wire:model.live="status"
                    :options="['' => 'Semua Status', 'active' => 'Aktif', 'inactive' => 'Nonaktif']"
                    class="w-full sm:w-48"
                    allow-empty="false"
                />
            </x-slot>
            <x-slot name="right">
                <x-page-size-select wire:model.live="perPage" />
            </x-slot>
        </x-table.toolbar>

        <x-table.data-table
            :rows="$users"
            :columns="[
                ['key' => 'name', 'label' => 'Nama Lengkap', 'sortable' => true],
                ['key' => 'email', 'label' => 'Email', 'sortable' => true],
                ['key' => 'roles', 'label' => 'Peran', 'render' => function ($row) { return $row->roles->pluck('name')->implode(', ') ?: '—'; }],
                ['key' => 'is_active', 'label' => 'Status', 'type' => 'badge', 'map' => ['1' => ['label' => 'Aktif', 'intent' => 'success'], '0' => ['label' => 'Nonaktif', 'intent' => 'neutral']], 'sortable' => true],
                ['key' => 'last_login_at', 'label' => 'Terakhir Masuk', 'type' => 'datetime', 'sortable' => true],
                ['type' => 'actions', 'label' => 'Aksi'],
            ]"
            :row-actions="function ($user) {
                $actions = [];
                if (auth()->user()->can('user.view')) {
                    $actions[] = ['label' => 'Detail', 'icon' => 'eye', 'href' => route('admin.users.show', $user->id), 'tooltip' => 'Lihat Detail'];
                }
                if (auth()->user()->can('user.update')) {
                    $actions[] = ['label' => 'Edit', 'icon' => 'pencil', 'href' => route('admin.users.edit', $user->id), 'tooltip' => 'Edit Pengguna'];
                }
                if (auth()->user()->can('user.delete') && $user->id !== auth()->id()) {
                    $actions[] = ['label' => 'Hapus', 'icon' => 'trash', 'intent' => 'danger', 'tooltip' => 'Hapus Pengguna'];
                }
                return $actions;
            }"
            :sort="$sort"
            :direction="$direction"
            :loading="false"
        />
    </x-content-card>
</div>