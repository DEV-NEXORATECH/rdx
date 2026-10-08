<div>
    <x-breadcrumbs :items="['Profil Saya']" />
    <x-page-header title="Profil Saya" description="Kelola biodata, identitas, dan dokumen akun Anda." />

    @php($user = auth()->user())

    <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,0.8fr)_minmax(0,1.8fr)]">
        <x-content-card class="overflow-hidden" padding="false">
            <div class="bg-primary px-6 py-8 text-center text-white">
                <div class="mx-auto flex h-28 w-28 items-center justify-center overflow-hidden rounded-full border-4 border-white/80 bg-white/20 shadow-lg">
                    @if ($user->profile_photo_path)
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($user->profile_photo_path) }}" alt="Foto {{ $user->name }}" class="h-full w-full object-cover">
                    @else
                        <span class="text-4xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    @endif
                </div>
                <h2 class="mt-4 text-xl font-bold">{{ $user->name }}</h2>
                <p class="mt-1 text-sm text-white/75">{{ $user->email }}</p>
                <p class="mt-4 inline-flex rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wider">{{ $user->getRoleNames()->first() ?? 'User' }}</p>
            </div>
            <div class="space-y-4 p-6">
                <div class="flex items-center justify-between border-b border-border pb-3 text-sm"><span class="text-muted">Status akun</span><x-badge>{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</x-badge></div>
                <div class="flex items-center justify-between text-sm"><span class="text-muted">Bergabung</span><span class="font-medium text-heading">{{ $user->created_at?->format('d M Y') }}</span></div>
                <label class="block text-sm font-semibold text-heading">Foto profil
                    <input wire:model="profile_photo" type="file" accept="image/*" class="mt-2 block w-full text-sm text-muted file:mr-3 file:rounded-lg file:border-0 file:bg-primary file:px-3 file:py-2 file:font-semibold file:text-white">
                </label>
                @error('profile_photo') <p class="text-xs text-danger">{{ $message }}</p> @enderror
            </div>
        </x-content-card>

        <x-content-card title="Biodata Pengguna" description="Informasi ini digunakan untuk identitas dan administrasi internal." padding>
            <form wire:submit="save" class="space-y-6">
                <div>
                    <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-wide text-primary"><span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary-soft">1</span> Informasi akun</h3>
                    <div class="grid gap-4 md:grid-cols-2">
                        <x-form.form-field name="name" label="Nama Lengkap" required><input wire:model="name" type="text" class="w-full" required></x-form.form-field>
                        <x-form.form-field name="email" label="Email" required><input wire:model="email" type="email" class="w-full" required></x-form.form-field>
                        <x-form.form-field name="phone" label="Nomor Telepon"><input wire:model="phone" type="tel" class="w-full" placeholder="08xxxxxxxxxx"></x-form.form-field>
                        <x-form.form-field name="gender" label="Jenis Kelamin"><select wire:model="gender" class="w-full"><option value="">Pilih jenis kelamin</option><option value="male">Laki-laki</option><option value="female">Perempuan</option><option value="other">Lainnya</option></select></x-form.form-field>
                    </div>
                </div>
                <div class="border-t border-border pt-6">
                    <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-wide text-primary"><span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary-soft">2</span> Identitas pribadi</h3>
                    <div class="grid gap-4 md:grid-cols-2">
                        <x-form.form-field name="nik" label="NIK / Nomor KTP"><input wire:model="nik" type="text" inputmode="numeric" class="w-full" placeholder="16 digit NIK"></x-form.form-field>
                        <x-form.form-field name="birth_date" label="Tanggal Lahir"><input wire:model="birth_date" type="date" class="w-full"></x-form.form-field>
                        <x-form.form-field name="address" label="Alamat Rumah" class="md:col-span-2"><textarea wire:model="address" rows="3" class="w-full" placeholder="Jalan, nomor rumah, RT/RW"></textarea></x-form.form-field>
                        <x-form.form-field name="city" label="Kota / Kabupaten"><input wire:model="city" type="text" class="w-full"></x-form.form-field>
                        <x-form.form-field name="postal_code" label="Kode Pos"><input wire:model="postal_code" type="text" class="w-full"></x-form.form-field>
                    </div>
                </div>
                <div class="border-t border-border pt-6">
                    <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-wide text-primary"><span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary-soft">3</span> Dokumen identitas</h3>
                    <label class="block text-sm font-semibold text-heading">Foto KTP
                        <input wire:model="ktp_photo" type="file" accept="image/*" class="mt-2 block w-full text-sm text-muted file:mr-3 file:rounded-lg file:border-0 file:bg-primary file:px-3 file:py-2 file:font-semibold file:text-white">
                    </label>
                    <p class="mt-2 text-xs text-muted">Format JPG, PNG, atau WEBP. Maksimal 4 MB.</p>
                    @error('ktp_photo') <p class="text-xs text-danger">{{ $message }}</p> @enderror
                    @if ($user->ktp_photo_path)<a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($user->ktp_photo_path) }}" target="_blank" class="mt-3 inline-flex text-sm font-semibold text-primary hover:underline">Lihat dokumen KTP tersimpan</a>@endif
                </div>
                <div class="flex flex-wrap items-center gap-3 border-t border-border pt-6">
                    <x-button type="submit" variant="primary">Simpan Biodata</x-button>
                    <a href="{{ route('account.change-password') }}" class="rounded-lg border border-border px-4 py-2 text-sm font-semibold text-heading hover:border-primary hover:text-primary">Ubah Kata Sandi</a>
                </div>
                @if (session('status')) <p class="text-sm font-medium text-success">{{ session('status') }}</p> @endif
            </form>
        </x-content-card>
    </div>
</div>
