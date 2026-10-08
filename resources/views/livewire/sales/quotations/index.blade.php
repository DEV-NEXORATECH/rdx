<div>
    <x-breadcrumbs :items="['Penawaran & Order']" />
    <x-page-header title="Penawaran & Order" description="Input dan kelola order beserta kalkulasi laba." />

    <div class="mt-6 rounded-2xl border border-brand-brown/25 bg-brand-brown-soft/45 p-3 sm:p-4">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <input wire:model.live="search" type="search" placeholder="Cari booking atau customer..." class="w-full bg-white lg:max-w-sm">
            <div class="flex flex-wrap gap-2"><a href="{{ route('sales.quotations.export.csv', ['date_from' => $dateFrom, 'date_to' => $dateTo, 'customer' => $customerFilter, 'marketing' => $marketingFilter, 'status' => $statusFilter]) }}" class="focus-ring rounded-lg border border-brand-brown/35 bg-white px-3 py-2 text-sm font-semibold text-brand-brown-hover transition-colors hover:bg-brand-brown-soft">Excel/CSV</a><a href="{{ route('sales.quotations.export.pdf', ['date_from' => $dateFrom, 'date_to' => $dateTo, 'customer' => $customerFilter, 'marketing' => $marketingFilter, 'status' => $statusFilter]) }}" target="_blank" class="focus-ring rounded-lg border border-brand-brown/35 bg-white px-3 py-2 text-sm font-semibold text-brand-brown-hover transition-colors hover:bg-brand-brown-soft">PDF</a><x-button href="{{ route('sales.quotations.create') }}" variant="primary" icon="plus">Input Order</x-button></div>
        </div>
        <div class="mt-3 grid gap-2 sm:grid-cols-2 lg:grid-cols-5"><input wire:model.live="dateFrom" type="date" class="w-full"><input wire:model.live="dateTo" type="date" class="w-full"><select wire:model.live="customerFilter" class="w-full"><option value="">Semua Customer</option>@foreach($customers as $customer)<option value="{{ $customer }}">{{ $customer }}</option>@endforeach</select><select wire:model.live="marketingFilter" class="w-full"><option value="">Semua Marketing</option>@foreach($marketings as $marketing)<option value="{{ $marketing }}">{{ $marketing }}</option>@endforeach</select><select wire:model.live="statusFilter" class="w-full"><option value="">Semua Status</option><option value="draft">Draft</option><option value="confirmed">Confirmed</option><option value="completed">Completed</option><option value="cancelled">Cancelled</option></select></div>
    </div>

    <x-content-card class="mt-4" :padding="false" overflow-visible>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-muted">
                    <tr>
                        @foreach (['booking_number' => 'No. Booking', 'customer' => 'Customer', 'order_date' => 'Tanggal'] as $column => $label)
                            <th class="whitespace-nowrap px-5 py-3">
                                <button type="button" wire:click="sortBy('{{ $column }}')" class="hover:text-heading">{{ $label }} ↕</button>
                            </th>
                        @endforeach
                        <th class="px-5 py-3">Tujuan</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Harga Jual</th>
                        <th class="px-5 py-3 text-right">Laba</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-5 py-3 font-semibold text-heading"><a href="{{ route('sales.quotations.show', $order) }}" class="hover:text-primary hover:underline">{{ $order->booking_number }}</a></td>
                            <td class="whitespace-nowrap px-5 py-3">{{ $order->customer }}</td>
                            <td class="whitespace-nowrap px-5 py-3">{{ $order->order_date->format('d/m/Y') }}</td>
                            <td class="px-5 py-3">{{ $order->destination }}</td>
                            <td class="px-5 py-3"><x-badge>{{ ucfirst($order->status) }}</x-badge></td>
                            <td class="whitespace-nowrap px-5 py-3 text-right">{{ \App\Domains\Shared\Support\Money::format($order->selling_price) }}</td>
                            <td class="whitespace-nowrap px-5 py-3 text-right font-semibold text-success">{{ \App\Domains\Shared\Support\Money::format($order->profit) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-10 text-center text-muted">Belum ada order.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-border px-5 py-3">{{ $orders->links() }}</div>
    </x-content-card>
</div>
