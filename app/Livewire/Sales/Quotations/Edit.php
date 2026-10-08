<?php

namespace App\Livewire\Sales\Quotations;

use App\Models\MasterRecord;
use App\Models\Order;
use Illuminate\Validation\Rules;

class Edit extends Create
{
    public function mount(?Order $order = null): void
    {
        abort_if($order === null, 404);
        $this->editingId = $order->id;
        $this->tariff_id = $order->tariff_id;
        $this->origin = (string) $order->origin;
        $this->order_date = $order->order_date->format('Y-m-d');
        foreach (['shipper', 'consignee', 'customer', 'destination', 'shipping_line', 'container_type', 'marketing'] as $field) {
            $this->{$field} = $order->{$field};
        }
        $this->status = (string) $order->status;
        foreach (['selling_price', 'capital_door', 'capital_of', 'capital_ops', 'capital_other'] as $field) {
            $this->{$field} = (float) $order->{$field};
        }
        $this->ppn_rate = $order->ppn_rate === null ? null : (float) $order->ppn_rate;
        $this->pph_rate = $order->pph_rate === null ? null : (float) $order->pph_rate;
    }

    public function save(): void
    {
        $this->validate([
            'order_date' => ['required', 'date'], 'tariff_id' => ['nullable', 'exists:tariffs,id'], 'origin' => ['required', 'string', 'max:255'], 'shipper' => ['required', 'string', 'max:255'],
            'consignee' => ['required', 'string', 'max:255'], 'customer' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'], 'shipping_line' => ['required', 'string', 'max:255'],
            'container_type' => ['required', 'string', 'max:100'], 'marketing' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:draft,confirmed,completed,cancelled'],
            'selling_price' => ['required', 'numeric', 'min:0'], 'capital_door' => ['nullable', 'numeric', 'min:0'],
            'capital_of' => ['nullable', 'numeric', 'min:0'], 'capital_ops' => ['nullable', 'numeric', 'min:0'],
            'capital_other' => ['nullable', 'numeric', 'min:0'], 'ppn_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'pph_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        Order::findOrFail($this->editingId)->update([
            'tariff_id' => $this->tariff_id, 'order_date' => $this->order_date, 'origin' => $this->origin, 'shipper' => $this->shipper, 'consignee' => $this->consignee,
            'customer' => $this->customer, 'destination' => $this->destination, 'shipping_line' => $this->shipping_line,
            'container_type' => $this->container_type, 'marketing' => $this->marketing, 'status' => $this->status, 'selling_price' => $this->selling_price,
            'capital_door' => $this->capital_door, 'capital_of' => $this->capital_of, 'capital_ops' => $this->capital_ops,
            'capital_other' => $this->capital_other, 'ppn_rate' => $this->ppn_rate, 'pph_rate' => $this->pph_rate,
            'ppn_amount' => $this->ppnAmount, 'pph_amount' => $this->pphAmount, 'profit' => $this->profit,
            'profit_percentage' => $this->profitPercentage,
        ]);

        session()->flash('status', 'Order berhasil diperbarui.');
        $this->redirectRoute('sales.quotations.index');
    }

    public function render()
    {
        $masters = MasterRecord::whereIn('type', ['shippers', 'consignees', 'customers', 'locations', 'shipping-lines', 'container-types', 'marketings'])
            ->where('is_active', true)->orderBy('name')->get()->groupBy('type');
        return view('livewire.sales.quotations.edit', compact('masters'))->title('Edit Order');
    }
}
