<x-layouts.app>
    <div>
        <x-breadcrumbs :items="[$title]" />
        <x-page-header :title="$title" :description="$description" />

        <x-content-card class="mt-6" padding>
            <div class="flex items-start gap-3">
                <div class="rounded-lg bg-primary-soft p-2 text-primary">
                    <x-icon name="information" class="h-5 w-5" />
                </div>
                <div>
                    <h2 class="font-semibold text-heading">Modul siap dikembangkan</h2>
                    <p class="mt-1 text-sm text-muted">Route dan navigasi untuk modul ini sudah tersedia. Fitur operasionalnya akan ditambahkan pada tahap berikutnya.</p>
                </div>
            </div>
        </x-content-card>
    </div>
</x-layouts.app>
