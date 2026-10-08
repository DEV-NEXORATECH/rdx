<div>
    <x-breadcrumbs :items="['Profil Saya']" />
    <x-page-header title="Profil Saya" description="Kelola informasi akun Anda." />

    <x-content-card class="mt-6 max-w-2xl" padding>
        <form wire:submit="save" class="space-y-4">
            <x-form.form-field name="name" label="Nama" required>
                <input wire:model="name" id="name" type="text" class="w-full" required>
            </x-form.form-field>
            <x-form.form-field name="email" label="Email" required>
                <input wire:model="email" id="email" type="email" class="w-full" required>
            </x-form.form-field>
            <x-button type="submit" variant="primary">Simpan Perubahan</x-button>
        </form>
    </x-content-card>
</div>
