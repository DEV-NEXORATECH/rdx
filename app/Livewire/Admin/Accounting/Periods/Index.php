<?php

namespace App\Livewire\Admin\Accounting\Periods;

use App\Models\AccountingPeriod;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public string $status = '';
    public function updatingStatus(): void { $this->resetPage(); }
    public function delete(int $id): void { abort_unless(auth()->user()?->can('period.delete'), 403); AccountingPeriod::findOrFail($id)->delete(); session()->flash('status', 'Periode berhasil dihapus.'); }
    public function render() { $periods = AccountingPeriod::when($this->status !== '', fn ($q) => $q->where('status', $this->status))->latest('starts_on')->paginate(12); return view('livewire.admin.accounting.periods.index', compact('periods'))->title('Periode Akuntansi'); }
}
