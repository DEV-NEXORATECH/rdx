<?php

namespace App\Livewire\Pricing\Tariffs;

use App\Models\Tariff;

class Edit extends Create
{
    public ?int $editingId = null;
    public function mount(Tariff $tariff): void
    {
        $this->editingId = $tariff->id;
        foreach (['origin', 'destination', 'shipping_line', 'container_type', 'valid_from', 'valid_until'] as $field) $this->{$field} = (string) ($tariff->{$field} ?? '');
        $this->selling_price = (float) $tariff->selling_price; $this->capital_price = (float) $tariff->capital_price; $this->is_active = $tariff->is_active;
    }
    public function save(): void
    {
        $this->validate(['origin' => ['required', 'string', 'max:255'], 'destination' => ['required', 'string', 'max:255'], 'shipping_line' => ['nullable', 'string', 'max:255'], 'container_type' => ['required', 'string', 'max:100'], 'selling_price' => ['required', 'numeric', 'min:0'], 'capital_price' => ['nullable', 'numeric', 'min:0'], 'valid_from' => ['nullable', 'date'], 'valid_until' => ['nullable', 'date', 'after_or_equal:valid_from']]);
        Tariff::findOrFail($this->editingId)->update($this->only(['origin', 'destination', 'shipping_line', 'container_type', 'selling_price', 'capital_price', 'valid_from', 'valid_until', 'is_active']));
        session()->flash('status', 'Tarif berhasil diperbarui.'); $this->redirectRoute('pricing.tariffs.index');
    }
    public function render() { return view('livewire.pricing.tariffs.edit')->title('Edit Tarif'); }
}
