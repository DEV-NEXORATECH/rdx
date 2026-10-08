<div>
    <x-breadcrumbs :items="['Ubah Kata Sandi']" />
    <x-page-header title="Ubah Kata Sandi" description="Perbarui kata sandi akun Anda secara berkala." />

    <x-content-card class="mt-6 max-w-2xl" padding>
        <form wire:submit="save" class="space-y-4">
            <x-form.form-field name="currentPassword" label="Kata Sandi Saat Ini" required>
                <input wire:model="currentPassword" id="currentPassword" type="password" class="w-full" required>
            </x-form.form-field>
            <x-form.form-field name="password" label="Kata Sandi Baru" required>
                <input wire:model="password" id="password" type="password" class="w-full" required>
            </x-form.form-field>
            <x-form.form-field name="password_confirmation" label="Ulangi Kata Sandi Baru" required>
                <input wire:model="password_confirmation" id="password_confirmation" type="password" class="w-full" required>
            </x-form.form-field>
            <x-button type="submit" variant="primary">Ubah Kata Sandi</x-button>
        </form>
    </x-content-card>
</div>
