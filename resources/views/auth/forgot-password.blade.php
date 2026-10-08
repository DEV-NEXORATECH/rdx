<x-layouts.guest :title="'Lupa Kata Sandi'">
    <div class="w-full max-w-sm">
        <div class="mb-8 flex flex-col items-center">
            <x-app.brand class="scale-125" />
            <h1 class="mt-4 text-center text-2xl font-bold tracking-tight text-heading">
                Reset Kata Sandi
            </h1>
            <p class="mt-1 text-center text-sm text-muted">
                Masukkan email terdaftar untuk menerima tautan reset.
            </p>
        </div>

        <div class="rounded-2xl border border-border bg-surface p-6 shadow-sm">
            @if (session('status'))
                <div class="mb-4 rounded-lg border border-success bg-success-soft px-4 py-3 text-sm text-success" role="status">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <x-form.form-field name="email" label="Email" :error-key="'email'" required>
                    <x-form.input
                        name="email"
                        type="email"
                        :value="old('email')"
                        autofocus
                        autocomplete="email"
                        placeholder="nama@perusahaan.com"
                        :class="['w-full', $errors->has('email') ? 'input-error' : '']"
                    />
                </x-form.form-field>

                <x-button type="submit" variant="primary" class="w-full" icon="paper-airplane">
                    Kirim Tautan Reset
                </x-button>
            </form>
        </div>

        <p class="mt-6 text-center">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-1 text-sm font-medium text-primary hover:text-primary-hover">
                <x-icon name="arrow-left" class="h-4 w-4" />
                Kembali ke halaman masuk
            </a>
        </p>
    </div>
</x-layouts.guest>