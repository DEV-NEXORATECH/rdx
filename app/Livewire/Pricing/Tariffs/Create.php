<?php

namespace App\Livewire\Pricing\Tariffs;

use App\Models\Tariff;
use Livewire\Component;

class Create extends Component
{
    public string $origin = ''; public string $destination = ''; public string $shipping_line = ''; public string $container_type = '';
    public float $selling_price = 0; public float $capital_price = 0; public string $valid_from = ''; public string $valid_until = ''; public bool $is_active = true;
    public function save(): void
    {
        $this->validate(['origin' => ['required', 'string', 'max:255'], 'destination' => ['required', 'string', 'max:255'], 'shipping_line' => ['nullable', 'string', 'max:255'], 'container_type' => ['required', 'string', 'max:100'], 'selling_price' => ['required', 'numeric', 'min:0'], 'capital_price' => ['nullable', 'numeric', 'min:0'], 'valid_from' => ['nullable', 'date'], 'valid_until' => ['nullable', 'date', 'after_or_equal:valid_from']]);
        Tariff::create($this->only(['origin', 'destination', 'shipping_line', 'container_type', 'selling_price', 'capital_price', 'valid_from', 'valid_until', 'is_active']));
        session()->flash('status', 'Tarif berhasil disimpan.'); $this->redirectRoute('pricing.tariffs.index');
    }
    public function render() { return view('livewire.pricing.tariffs.create')->title('Tambah Tarif'); }
}
