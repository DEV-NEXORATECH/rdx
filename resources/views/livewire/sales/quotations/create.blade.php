<div>
    <x-breadcrumbs :items="['Penawaran & Order', 'Input Order']" />
    <x-page-header title="Input Order" description="Isi data order dan nilai modal untuk menghitung laba otomatis." />

    <form wire:submit="save" class="mt-6 space-y-6">
        <x-content-card title="Data Order" padding>
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <x-form.form-field name="tariff_id" label="Tarif Domestik">
                    <select wire:model.live="tariff_id" id="tariff_id" class="w-full">
                        <option value="">Input manual / pilih tarif</option>
                        @foreach ($tariffs as $tariff)
                            <option value="{{ $tariff->id }}">{{ $tariff->origin }} → {{ $tariff->destination }} / {{ $tariff->container_type }}</option>
                        @endforeach
                    </select>
                </x-form.form-field>
                <x-form.form-field name="origin" label="Asal" required>
                    <input wire:model="origin" id="origin" type="text" class="w-full" required>
                </x-form.form-field>
                @foreach ([['order_date','Tanggal Order','date']] as [$name, $label, $type])
                    <x-form.form-field :name="$name" :label="$label" required>
                        <input wire:model="{{ $name }}" id="{{ $name }}" type="{{ $type }}" class="w-full" required>
                    </x-form.form-field>
                @endforeach
                @foreach ([['shipper','Shipper','shippers'],['consignee','Consignee','consignees'],['customer','Customer','customers'],['destination','Tujuan','locations'],['shipping_line','Shipping Line','shipping-lines'],['container_type','Jenis Container','container-types'],['marketing','Marketing','marketings']] as [$name, $label, $masterType])
                    <x-form.form-field :name="$name" :label="$label" required>
                        <select wire:model="{{ $name }}" id="{{ $name }}" class="w-full" required>
                            <option value="">Pilih {{ $label }}</option>
                            @foreach ($masters->get($masterType, []) as $master)
                                <option value="{{ $master->name }}">{{ $master->code ? $master->code.' — ' : '' }}{{ $master->name }}</option>
                            @endforeach
                        </select>
                        @if ($masters->get($masterType, collect())->isEmpty())
                            <p class="mt-1 text-xs text-warning">Belum ada data master {{ strtolower($label) }}.</p>
                        @endif
                    </x-form.form-field>
                @endforeach
                <x-form.form-field name="status" label="Status Order" required>
                    <select wire:model="status" id="status" class="w-full" required>
                        <option value="draft">Draft</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </x-form.form-field>
            </div>
        </x-content-card>

        <x-content-card title="Harga dan Modal" padding>
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach ([['selling_price','Harga Jual'],['capital_door','Modal Door'],['capital_of','Modal O/F'],['capital_ops','Modal Operasional'],['capital_other','Modal Lainnya']] as [$name, $label])
                    <x-form.form-field :name="$name" :label="$label">
                        <input wire:model.live="{{ $name }}" id="{{ $name }}" type="number" min="0" step="0.01" class="w-full">
                    </x-form.form-field>
                @endforeach
            </div>
            <div class="mt-4 rounded-lg bg-slate-50 p-4 text-sm">
                <div class="flex justify-between"><span>Total Modal</span><strong>{{ \App\Domains\Shared\Support\Money::format($this->totalCapital) }}</strong></div>
                <div class="mt-2 flex justify-between text-success"><span>Laba</span><strong>{{ \App\Domains\Shared\Support\Money::format($this->profit) }}</strong></div>
                <div class="mt-2 flex justify-between"><span>Persentase Laba</span><strong>{{ number_format($this->profitPercentage, 2, ',', '.') }}%</strong></div>
            </div>
        </x-content-card>

        <x-content-card title="Pajak (Opsional)" description="Kosongkan jika tidak berlaku." padding>
            <div class="grid gap-4 md:grid-cols-2">
                <x-form.form-field name="ppn_rate" label="PPN (%)">
                    <input wire:model.live="ppn_rate" id="ppn_rate" type="number" min="0" max="100" step="0.01" class="w-full" placeholder="Contoh: 11">
                    <p class="mt-1 text-xs text-muted">Nominal: {{ \App\Domains\Shared\Support\Money::format($this->ppnAmount) }}</p>
                </x-form.form-field>
                <x-form.form-field name="pph_rate" label="PPh (%)">
                    <input wire:model.live="pph_rate" id="pph_rate" type="number" min="0" max="100" step="0.01" class="w-full" placeholder="Contoh: 2,5">
                    <p class="mt-1 text-xs text-muted">Nominal: {{ \App\Domains\Shared\Support\Money::format($this->pphAmount) }}</p>
                </x-form.form-field>
            </div>
        </x-content-card>

        <div class="flex justify-end gap-2">
            <x-button href="{{ route('sales.quotations.index') }}" variant="secondary">Batal</x-button>
            <x-button type="submit" variant="primary">Simpan Order</x-button>
        </div>
    </form>
</div>
