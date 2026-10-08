<x-layouts.guest :title="'Masuk'">
    <div class="grid min-h-screen w-full overflow-hidden bg-surface lg:grid-cols-[0.86fr_1.14fr]">
        <section class="order-2 flex items-center px-6 py-10 sm:px-12 lg:order-1 lg:px-[clamp(3rem,7vw,9rem)]">
            <div class="mx-auto w-full max-w-md">
                <div class="mb-9">
                    <div class="flex justify-center">
                        <a href="{{ route('dashboard') }}" aria-label="Kembali ke dashboard">
                            <img src="{{ Vite::asset('resources/img/brand-logo.png') }}" alt="Logo" class="h-20 w-20 rounded-full object-cover shadow-sm ring-1 ring-border">
                        </a>
                    </div>
                    <div class="mt-10">
                        <h1 class="text-center text-3xl font-bold tracking-tight text-heading sm:text-4xl">Selamat datang kembali</h1>
                    </div>
                </div>

                <div class="rounded-2xl border border-border bg-white p-7 shadow-lg shadow-slate-200/60">
                    @if (session('status'))
                        <div class="mb-4 rounded-lg border border-success bg-success-soft px-4 py-3 text-sm text-success" role="status">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf
                        <x-form.form-field name="email" label="Email" :error-key="'email'" required>
                            <x-form.input name="email" type="email" value="{{ old('email') }}" autofocus autocomplete="email" placeholder="nama@perusahaan.com" class="w-full{{ $errors->has('email') ? ' input-error' : '' }}" />
                        </x-form.form-field>
                        <x-form.form-field name="password" label="Kata Sandi" :error-key="'password'" required>
                            <x-form.input name="password" type="password" autocomplete="current-password" placeholder="••••••••" class="w-full{{ $errors->has('password') ? ' input-error' : '' }}" />
                        </x-form.form-field>
                        <div class="flex items-center justify-between">
                            <label class="flex cursor-pointer items-center gap-2">
                                <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-border text-primary focus:ring-primary/40">
                                <span class="text-sm text-muted">Ingat saya</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="text-sm font-medium text-primary hover:text-primary-hover">Lupa kata sandi?</a>
                        </div>
                        <x-button type="submit" variant="primary" class="mt-2 w-full" icon="arrow-right">Masuk ke Dashboard</x-button>
                    </form>
                </div>

            </div>
        </section>

        <aside class="relative order-1 min-h-[22rem] overflow-hidden bg-primary lg:order-2 lg:min-h-screen">
            <img src="{{ Vite::asset('resources/img/login-logistics.png') }}" alt="Pelabuhan dan kapal logistik" class="absolute inset-0 h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-br from-primary/55 via-primary/10 to-slate-950/65"></div>
        </aside>
    </div>
</x-layouts.guest>
