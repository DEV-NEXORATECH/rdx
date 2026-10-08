<?php

namespace App\Livewire\Pricing\Tariffs;

use App\Models\Tariff;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public string $search = '';
    public string $status = '';
    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingStatus(): void { $this->resetPage(); }
    public function delete(int $id): void { abort_unless(auth()->user()?->can('tariff.delete'), 403); Tariff::findOrFail($id)->delete(); session()->flash('status', 'Tarif berhasil dihapus.'); }
    public function render()
    {
        $tariffs = Tariff::query()->when($this->status !== '', fn ($q) => $q->where('is_active', $this->status === 'active'))->when($this->search !== '', fn ($q) => $q->where(fn ($x) => $x->where('origin', 'like', '%'.$this->search.'%')->orWhere('destination', 'like', '%'.$this->search.'%')->orWhere('shipping_line', 'like', '%'.$this->search.'%')))->latest()->paginate(10);
        return view('livewire.pricing.tariffs.index', compact('tariffs'))->title('Tarif Domestik');
    }
}
