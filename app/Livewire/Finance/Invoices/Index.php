<?php

namespace App\Livewire\Finance\Invoices;

use App\Models\Invoice;
use App\Models\JournalEntry;
use App\Domains\Accounting\Actions\JournalAuditor;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public string $search = '';
    public string $status = '';
    public string $sort = 'created_at';
    public string $direction = 'desc';
    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingStatus(): void { $this->resetPage(); }
    public function sortBy(string $column): void
    {
        if (! in_array($column, ['created_at', 'total', 'status'], true)) return;
        if ($this->sort === $column) $this->direction = $this->direction === 'asc' ? 'desc' : 'asc';
        else [$this->sort, $this->direction] = [$column, 'asc'];
    }
    public function delete(int $id): void
    {
        abort_unless(auth()->user()?->can('invoice.delete'), 403);
        $invoice = Invoice::findOrFail($id);
        if ($journal = JournalEntry::where('reference', $invoice->invoice_number)->first()) JournalAuditor::reverse($journal, 'Penghapusan tagihan '.$invoice->invoice_number);
        $invoice->delete();
        session()->flash('status', 'Tagihan berhasil dihapus.');
    }
    public function render()
    {
        $invoices = Invoice::with('jobOrder.order')->when($this->status !== '', fn ($q) => $q->where('status', $this->status))->when($this->search !== '', fn ($q) => $q->where('invoice_number', 'like', '%'.$this->search.'%')->orWhereHas('jobOrder.order', fn ($o) => $o->where('booking_number', 'like', '%'.$this->search.'%')->orWhere('customer', 'like', '%'.$this->search.'%')))->orderBy($this->sort, $this->direction)->paginate(10);
        return view('livewire.finance.invoices.index', compact('invoices'))->title('Tagihan');
    }
}
