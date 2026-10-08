<div>
    <x-breadcrumbs :items="[$meta['title'], $editingId ? 'Edit' : 'Tambah']" />
    <x-page-header :title="($editingId ? 'Edit ' : 'Tambah ').$meta['singular']" description="Lengkapi informasi data master." />

    <x-content-card class="mt-6 max-w-2xl" padding>
        <form wire:submit="save" class="space-y-4">
            <x-form.form-field name="code" label="Kode">
                <input wire:model="code" id="code" type="text" class="w-full">
            </x-form.form-field>
            <x-form.form-field name="name" :label="$meta['singular']" required>
                <input wire:model="name" id="name" type="text" class="w-full" required>
            </x-form.form-field>
            <x-form.form-field name="notes" label="Catatan">
                <textarea wire:model="notes" id="notes" rows="4" class="w-full"></textarea>
            </x-form.form-field>
            @if (in_array($type, ['companies', 'customers', 'vendors', 'shippers', 'consignees', 'marketings'], true))
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-form.form-field name="email" label="Email"><input wire:model="email" type="email" class="w-full"></x-form.form-field>
                    <x-form.form-field name="phone" label="Telepon"><input wire:model="phone" class="w-full"></x-form.form-field>
                    <x-form.form-field name="address" label="Alamat" class="sm:col-span-2"><textarea wire:model="address" rows="2" class="w-full"></textarea></x-form.form-field>
                    <x-form.form-field name="tax_number" label="NPWP"><input wire:model="tax_number" class="w-full"></x-form.form-field>
                    @if ($type === 'customers')
                        <x-form.form-field name="contact_person" label="PIC Customer"><input wire:model="contact_person" class="w-full"></x-form.form-field>
                        <x-form.form-field name="payment_terms" label="Termin Pembayaran"><input wire:model="payment_terms" placeholder="Contoh: 30 hari" class="w-full"></x-form.form-field>
                        <x-form.form-field name="credit_limit" label="Limit Kredit"><input wire:model="credit_limit" type="number" min="0" step="0.01" class="w-full"></x-form.form-field>
                    @elseif ($type === 'vendors')
                        <x-form.form-field name="contact_person" label="PIC Vendor"><input wire:model="contact_person" class="w-full"></x-form.form-field>
                        <x-form.form-field name="bank_name" label="Nama Bank"><input wire:model="bank_name" class="w-full"></x-form.form-field>
                        <x-form.form-field name="bank_account" label="Nomor Rekening"><input wire:model="bank_account" class="w-full"></x-form.form-field>
                    @endif
                </div>
            @elseif ($type === 'vehicles')
                <div class="grid gap-4 sm:grid-cols-2"><x-form.form-field name="plate_number" label="Nomor Polisi" required><input wire:model="plate_number" class="w-full"></x-form.form-field><x-form.form-field name="vehicle_type" label="Tipe Kendaraan"><input wire:model="vehicle_type" class="w-full"></x-form.form-field><x-form.form-field name="capacity" label="Kapasitas"><input wire:model="capacity" class="w-full"></x-form.form-field><x-form.form-field name="ownership" label="Kepemilikan"><select wire:model="ownership" class="w-full"><option value="">Pilih</option><option value="own">Milik Sendiri</option><option value="rental">Sewa</option></select></x-form.form-field><x-form.form-field name="year" label="Tahun Kendaraan"><input wire:model="year" type="number" min="1900" max="2100" class="w-full"></x-form.form-field><x-form.form-field name="kir_expiry" label="Berlaku KIR"><input wire:model="kir_expiry" type="date" class="w-full"></x-form.form-field></div>
            @elseif ($type === 'drivers')
                <div class="grid gap-4 sm:grid-cols-2"><x-form.form-field name="phone" label="Telepon"><input wire:model="phone" class="w-full"></x-form.form-field><x-form.form-field name="license_number" label="Nomor SIM"><input wire:model="license_number" class="w-full"></x-form.form-field><x-form.form-field name="license_expiry" label="Berlaku SIM"><input wire:model="license_expiry" type="date" class="w-full"></x-form.form-field><x-form.form-field name="address" label="Alamat" class="sm:col-span-2"><textarea wire:model="address" rows="2" class="w-full"></textarea></x-form.form-field></div>
            @elseif ($type === 'shipping-lines')
                <div class="grid gap-4 sm:grid-cols-2"><x-form.form-field name="contact_person" label="PIC Shipping Line"><input wire:model="contact_person" class="w-full"></x-form.form-field><x-form.form-field name="phone" label="Telepon"><input wire:model="phone" class="w-full"></x-form.form-field><x-form.form-field name="scac_code" label="SCAC Code"><input wire:model="scac_code" class="w-full"></x-form.form-field><x-form.form-field name="website" label="Website"><input wire:model="website" type="url" class="w-full"></x-form.form-field><x-form.form-field name="address" label="Alamat" class="sm:col-span-2"><textarea wire:model="address" rows="2" class="w-full"></textarea></x-form.form-field></div>
            @elseif ($type === 'container-types')
                <div class="grid gap-4 sm:grid-cols-2"><x-form.form-field name="container_size" label="Ukuran Container" required><input wire:model="container_size" placeholder="20ft / 40ft / 40HC" class="w-full"></x-form.form-field><x-form.form-field name="payload_capacity" label="Kapasitas Muatan"><input wire:model="payload_capacity" class="w-full"></x-form.form-field><x-form.form-field name="tare_weight" label="Berat Kosong"><input wire:model="tare_weight" class="w-full"></x-form.form-field></div>
            @endif
            <label class="flex items-center gap-2 text-sm text-text">
                <input wire:model="is_active" type="checkbox" class="rounded border-border text-primary">
                Data aktif
            </label>
            <div class="flex gap-2 pt-2">
                <x-button type="submit" variant="primary">Simpan</x-button>
                <x-button :href="route($prefix.'.'.$type.'.index')" variant="secondary">Batal</x-button>
            </div>
        </form>
    </x-content-card>
</div>
