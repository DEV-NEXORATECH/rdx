<div>
    <x-breadcrumbs :items="['Dashboard']" />
    <x-page-header title="Dashboard" description="Ringkasan aktivitas operasional dan keuangan hari ini." />

    <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-kpi-card label="Job Order Aktif" :value="$activeJobs" icon="truck" hint="Dalam proses" />
        <x-kpi-card label="Total Order" :value="$orders" icon="document-text" hint="Order tersimpan" />
        <x-kpi-card label="Tagihan Terbuka" :value="\App\Domains\Shared\Support\Money::format($openInvoices)" icon="receipt" hint="Draft, issued, overdue" />
        <x-kpi-card label="Total Biaya" :value="\App\Domains\Shared\Support\Money::format($totalCosts)" icon="calculator" hint="Modal tercatat" />
    </div>

    <div class="mt-6 grid gap-4 sm:grid-cols-2">
        <x-content-card title="Pendapatan" padding>
            <p class="text-2xl font-bold text-heading">{{ \App\Domains\Shared\Support\Money::format($totalRevenue) }}</p>
        </x-content-card>
        <x-content-card title="Laba" padding>
            <p class="text-2xl font-bold text-success">{{ \App\Domains\Shared\Support\Money::format($totalProfit) }}</p>
        </x-content-card>
    </div>

    @php
        $chartMax = max(1, collect($months)->max(fn ($month) => max($month['revenue'], $month['costs'], $month['profit'])));
        $totalStatusOrders = max(1, array_sum($orderStatuses));
        $statusLabels = ['draft' => 'Draft', 'confirmed' => 'Confirmed', 'completed' => 'Completed', 'cancelled' => 'Cancelled'];
        $statusColors = ['draft' => 'bg-slate-400', 'confirmed' => 'bg-primary', 'completed' => 'bg-success', 'cancelled' => 'bg-danger'];
        $lineMax = max(1, collect($months)->max('revenue'));
        $lineRevenue = collect($months)->values()->map(fn ($month, $index) => (($index / max(1, count($months) - 1)) * 560).','. (190 - (($month['revenue'] / $lineMax) * 155)))->implode(' ');
        $lineProfit = collect($months)->values()->map(fn ($month, $index) => (($index / max(1, count($months) - 1)) * 560).','. (190 - (max(0, $month['profit']) / $lineMax * 155)))->implode(' ');
        $draftPct = (($orderStatuses['draft'] ?? 0) / $totalStatusOrders) * 100;
        $confirmedPct = (($orderStatuses['confirmed'] ?? 0) / $totalStatusOrders) * 100;
        $completedPct = (($orderStatuses['completed'] ?? 0) / $totalStatusOrders) * 100;
        $cancelledPct = (($orderStatuses['cancelled'] ?? 0) / $totalStatusOrders) * 100;
        $donutStyle = "background: conic-gradient(#94a3b8 0% {$draftPct}%, #315b9b {$draftPct}% ".($draftPct + $confirmedPct)."%, #16a34a ".($draftPct + $confirmedPct)."% ".($draftPct + $confirmedPct + $completedPct)."%, #dc2626 ".($draftPct + $confirmedPct + $completedPct)."% 100%);";
    @endphp

    <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1.5fr)_minmax(20rem,0.8fr)]">
        <x-content-card title="Performa 6 Bulan" description="Perbandingan pendapatan, biaya, dan laba berdasarkan transaksi tersimpan." padding>
            <div class="mt-5 flex items-end gap-3 sm:gap-5">
                @foreach ($months as $month)
                    <div class="min-w-0 flex-1">
                        <div class="flex h-52 items-end justify-center gap-1.5 rounded-lg bg-slate-50 px-1 pt-3 sm:gap-2">
                            <span title="Pendapatan: {{ \App\Domains\Shared\Support\Money::format($month['revenue']) }}" class="w-1/3 rounded-t-md bg-primary transition-all hover:opacity-80" style="height: {{ max(4, ($month['revenue'] / $chartMax) * 100) }}%"></span>
                            <span title="Biaya: {{ \App\Domains\Shared\Support\Money::format($month['costs']) }}" class="w-1/3 rounded-t-md bg-brand-brown transition-all hover:opacity-80" style="height: {{ max(4, ($month['costs'] / $chartMax) * 100) }}%"></span>
                            <span title="Laba: {{ \App\Domains\Shared\Support\Money::format($month['profit']) }}" class="w-1/3 rounded-t-md bg-success transition-all hover:opacity-80" style="height: {{ max(4, ($month['profit'] / $chartMax) * 100) }}%"></span>
                        </div>
                        <div class="mt-2 text-center text-xs font-medium text-muted">{{ $month['label'] }}</div>
                        <div class="text-center text-[11px] text-muted">{{ $month['orders'] }} order</div>
                    </div>
                @endforeach
            </div>
            <div class="mt-5 flex flex-wrap gap-x-5 gap-y-2 text-xs text-muted">
                <span class="inline-flex items-center gap-2"><i class="h-2.5 w-2.5 rounded-full bg-primary"></i>Pendapatan</span>
                <span class="inline-flex items-center gap-2"><i class="h-2.5 w-2.5 rounded-full bg-brand-brown"></i>Biaya</span>
                <span class="inline-flex items-center gap-2"><i class="h-2.5 w-2.5 rounded-full bg-success"></i>Laba</span>
            </div>
        </x-content-card>

        <x-content-card title="Status Order" description="Distribusi seluruh order yang tersimpan." padding>
            <div class="mt-5 space-y-4">
                @foreach ($statusLabels as $status => $label)
                    @php($count = (int) ($orderStatuses[$status] ?? 0))
                    <div>
                        <div class="mb-1.5 flex items-center justify-between text-sm"><span class="font-medium text-text">{{ $label }}</span><span class="text-muted">{{ $count }}</span></div>
                        <div class="h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full {{ $statusColors[$status] }}" style="width: {{ ($count / $totalStatusOrders) * 100 }}%"></div></div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6 rounded-xl bg-brand-brown-soft p-4"><p class="text-xs font-semibold uppercase tracking-wider text-brand-brown-hover">Total order</p><p class="mt-1 text-2xl font-bold text-heading">{{ $orders }}</p></div>
        </x-content-card>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1.5fr)_minmax(20rem,0.8fr)]">
        <x-content-card title="Tren Pendapatan &amp; Laba" description="Pergerakan nilai transaksi selama enam bulan terakhir." padding>
            <div class="mt-5 overflow-x-auto">
                <svg viewBox="0 0 600 220" class="min-w-[34rem] w-full" role="img" aria-label="Grafik tren pendapatan dan laba">
                    <g class="text-slate-200" stroke="currentColor" stroke-width="1"><line x1="0" y1="35" x2="600" y2="35" /><line x1="0" y1="85" x2="600" y2="85" /><line x1="0" y1="135" x2="600" y2="135" /><line x1="0" y1="190" x2="600" y2="190" /></g>
                    <polyline points="{{ $lineRevenue }}" fill="none" stroke="#315b9b" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    <polyline points="{{ $lineProfit }}" fill="none" stroke="#b89259" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    @foreach ($months as $index => $month)
                        @php($x = ($index / max(1, count($months) - 1)) * 560)
                        <circle cx="{{ $x }}" cy="{{ 190 - (($month['revenue'] / $lineMax) * 155) }}" r="4.5" fill="#315b9b" />
                        <circle cx="{{ $x }}" cy="{{ 190 - (max(0, $month['profit']) / $lineMax * 155) }}" r="4.5" fill="#b89259" />
                        <text x="{{ $x }}" y="215" text-anchor="middle" class="fill-slate-500 text-[11px]">{{ $month['label'] }}</text>
                    @endforeach
                </svg>
            </div>
            <div class="mt-3 flex gap-5 text-xs text-muted"><span class="inline-flex items-center gap-2"><i class="h-2.5 w-2.5 rounded-full bg-primary"></i>Pendapatan</span><span class="inline-flex items-center gap-2"><i class="h-2.5 w-2.5 rounded-full bg-brand-brown"></i>Laba</span></div>
        </x-content-card>

        <x-content-card title="Komposisi Status" description="Proporsi status order saat ini." padding>
            <div class="flex items-center justify-center py-2">
                <div class="relative h-44 w-44 rounded-full" style="{{ $donutStyle }}">
                    <div class="absolute inset-7 flex flex-col items-center justify-center rounded-full bg-white"><strong class="text-3xl text-heading">{{ $orders }}</strong><span class="text-xs text-muted">Total Order</span></div>
                </div>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-3 text-xs">
                @foreach ($statusLabels as $status => $label)
                    <span class="flex items-center gap-2 text-muted"><i class="h-2.5 w-2.5 rounded-full {{ $statusColors[$status] }}"></i>{{ $label }} <strong class="text-heading">{{ $orderStatuses[$status] ?? 0 }}</strong></span>
                @endforeach
            </div>
        </x-content-card>
    </div>

    <x-content-card class="mt-6" title="Order Terbaru" description="Lima order terakhir yang masuk ke sistem." padding>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="border-b border-border text-left text-xs uppercase tracking-wide text-muted"><tr><th class="pb-3 pr-4">No. Booking</th><th class="pb-3 pr-4">Customer</th><th class="pb-3 pr-4">Tanggal</th><th class="pb-3 pr-4">Status</th><th class="pb-3 text-right">Laba</th></tr></thead>
                <tbody class="divide-y divide-border">
                    @forelse ($recentOrders as $order)
                        <tr><td class="py-3 pr-4 font-semibold"><a href="{{ route('sales.quotations.show', $order) }}" class="text-primary hover:underline">{{ $order->booking_number }}</a></td><td class="py-3 pr-4">{{ $order->customer }}</td><td class="whitespace-nowrap py-3 pr-4">{{ $order->order_date->format('d/m/Y') }}</td><td class="py-3 pr-4"><x-badge>{{ ucfirst($order->status) }}</x-badge></td><td class="py-3 text-right font-semibold text-success">{{ \App\Domains\Shared\Support\Money::format($order->profit) }}</td></tr>
                    @empty
                        <tr><td colspan="5" class="py-8 text-center text-muted">Belum ada order terbaru.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-content-card>
</div>
