<?php

namespace App\Livewire\Sales\Quotations;

use App\Domains\Shared\Support\DocumentNumber;
use App\Models\MasterRecord;
use App\Models\Order;
use App\Models\Tariff;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Create extends Component
{
    public string $order_date = '';
    public ?int $tariff_id = null;
    public string $origin = '';
    public string $shipper = '';
    public string $consignee = '';
    public string $customer = '';
    public string $destination = '';
    public string $shipping_line = '';
    public string $container_type = '';
    public string $marketing = '';
    public string $status = 'draft';
    public float $selling_price = 0;
    public float $capital_door = 0;
    public float $capital_of = 0;
    public float $capital_ops = 0;
    public float $capital_other = 0;
    public ?float $ppn_rate = null;
    public ?float $pph_rate = null;

    public function updatedTariffId(?int $tariffId): void
    {
        if (! $tariffId) return;
        $tariff = Tariff::where('is_active', true)->find($tariffId);
        if (! $tariff) return;
        $this->origin = $tariff->origin;
        $this->destination = $tariff->destination;
        $this->shipping_line = (string) ($tariff->shipping_line ?? '');
        $this->container_type = $tariff->container_type;
        $this->selling_price = (float) $tariff->selling_price;
        $this->capital_other = (float) $tariff->capital_price;
    }

    public function updatedOrigin(): void { $this->applyMatchingTariff(); }
    public function updatedDestination(): void { $this->applyMatchingTariff(); }
    public function updatedShippingLine(): void { $this->applyMatchingTariff(); }
    public function updatedContainerType(): void { $this->applyMatchingTariff(); }

    protected function applyMatchingTariff(): void
    {
        if ($this->origin === '' || $this->destination === '' || $this->container_type === '') return;
        $tariff = Tariff::where('is_active', true)
            ->where('origin', $this->origin)
            ->where('destination', $this->destination)
            ->where('container_type', $this->container_type)
            ->when($this->shipping_line !== '', fn ($query) => $query->where(function ($line) { $line->where('shipping_line', $this->shipping_line)->orWhereNull('shipping_line'); }))
            ->where(function ($query) { $query->whereNull('valid_from')->orWhereDate('valid_from', '<=', $this->order_date); })
            ->where(function ($query) { $query->whereNull('valid_until')->orWhereDate('valid_until', '>=', $this->order_date); })
            ->latest()->first();

        if ($tariff && $this->tariff_id !== $tariff->id) {
            $this->tariff_id = $tariff->id;
            $this->selling_price = (float) $tariff->selling_price;
            $this->capital_other = (float) $tariff->capital_price;
        }
    }

    public function mount(?Order $order = null): void
    {
        $this->order_date = Carbon::today()->format('Y-m-d');
    }

    public function getTotalCapitalProperty(): float
    {
        return $this->capital_door + $this->capital_of + $this->capital_ops + $this->capital_other;
    }

    public function getPpnAmountProperty(): float
    {
        return $this->ppn_rate === null ? 0 : $this->selling_price * $this->ppn_rate / 100;
    }

    public function getPphAmountProperty(): float
    {
        return $this->pph_rate === null ? 0 : $this->selling_price * $this->pph_rate / 100;
    }

    public function getProfitProperty(): float
    {
        return $this->selling_price - $this->totalCapital;
    }

    public function getProfitPercentageProperty(): float
    {
        return $this->selling_price > 0 ? ($this->profit / $this->selling_price) * 100 : 0;
    }

    public function save(): void
    {
        $this->validate([
            'order_date' => ['required', 'date'],
            'tariff_id' => ['nullable', 'exists:tariffs,id'],
            'origin' => ['required', 'string', 'max:255'],
            'shipper' => ['required', 'string', 'max:255'],
            'consignee' => ['required', 'string', 'max:255'],
            'customer' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'shipping_line' => ['required', 'string', 'max:255'],
            'container_type' => ['required', 'string', 'max:100'],
            'marketing' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:draft,confirmed,completed,cancelled'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'capital_door' => ['nullable', 'numeric', 'min:0'],
            'capital_of' => ['nullable', 'numeric', 'min:0'],
            'capital_ops' => ['nullable', 'numeric', 'min:0'],
            'capital_other' => ['nullable', 'numeric', 'min:0'],
            'ppn_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'pph_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        Order::create([
            'booking_number' => DocumentNumber::nextBooking(),
            'tariff_id' => $this->tariff_id,
            'order_date' => $this->order_date,
            'origin' => $this->origin,
            'shipper' => $this->shipper,
            'consignee' => $this->consignee,
            'customer' => $this->customer,
            'destination' => $this->destination,
            'shipping_line' => $this->shipping_line,
            'container_type' => $this->container_type,
            'marketing' => $this->marketing,
            'status' => $this->status,
            'selling_price' => $this->selling_price,
            'capital_door' => $this->capital_door,
            'capital_of' => $this->capital_of,
            'capital_ops' => $this->capital_ops,
            'capital_other' => $this->capital_other,
            'ppn_rate' => $this->ppn_rate,
            'pph_rate' => $this->pph_rate,
            'ppn_amount' => $this->ppnAmount,
            'pph_amount' => $this->pphAmount,
            'profit' => $this->profit,
            'profit_percentage' => $this->profitPercentage,
        ]);

        session()->flash('status', 'Order berhasil disimpan.');
        $this->redirectRoute('sales.quotations.index');
    }

    public function render()
    {
        $masters = MasterRecord::query()
            ->whereIn('type', ['shippers', 'consignees', 'customers', 'locations', 'shipping-lines', 'container-types', 'marketings'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->groupBy('type');

        $tariffs = Tariff::where('is_active', true)
            ->when($this->origin !== '', fn ($query) => $query->where('origin', $this->origin))
            ->when($this->destination !== '', fn ($query) => $query->where('destination', $this->destination))
            ->when($this->shipping_line !== '', fn ($query) => $query->where(function ($line) { $line->where('shipping_line', $this->shipping_line)->orWhereNull('shipping_line'); }))
            ->when($this->container_type !== '', fn ($query) => $query->where('container_type', $this->container_type))
            ->orderBy('origin')->orderBy('destination')->get();

        return view('livewire.sales.quotations.create', compact('masters', 'tariffs'))->title('Input Order');
    }
}
