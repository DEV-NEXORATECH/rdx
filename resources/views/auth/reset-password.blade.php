<x-layouts.guest :title="'Reset Kata Sandi'">
    <div class="w-full max-w-sm">
        <div class="mb-8 flex flex-col items-center">
            <x-app.brand class="scale-125" />
            <h1 class="mt-4 text-center text-2xl font-bold tracking-tight text-heading">
                Buat Kata Sandi Baru
            </h1>
        </div>

        <div class="rounded-2xl border border-border bg-surface p-6 shadow-sm">
            <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <x-form.form-field name="email" label="Email" :error-key="'email'" required>
                    <x-form.input
                        name="email"
                        type="email"
                        :value="old('email', $email)"
                        autocomplete="email"
                        placeholder="nama@perusahaan.com"
                        :class="['w-full', $errors->has('email') ? 'input-error' : '']"
                    />
                </x-form.form-field>

                <x-form.form-field name="password" label="Kata Sandi Baru" :error-key="'password'" required>
                    <x-form.input
                        name="password"
                        type="password"
                        autocomplete="new-password"
                        placeholder="Minimum 8 karakter"
                        :class="['w-full', $errors->has('password') ? 'input-error' : '']"
                    />
                </x-form.form-field>

                <x-form.form-field name="password_confirmation" label="Ulangi Kata Sandi" :error-key="'password_confirmation'" required>
                    <x-form.input
                        name="password_confirmation"
                        type="password"
                        autocomplete="new-password"
                        placeholder="Ulangi kata sandi"
                        :class="['w-full', $errors->has('password_confirmation') ? 'input-error' : '']"
                    />
                </x-form.form-field>

                <x-button type="submit" variant="primary" class="w-full" icon="key">
                    Simpan Kata Sandi
                </x-button>
            </form>
        </div>
    </div>
</x-layouts.guest>