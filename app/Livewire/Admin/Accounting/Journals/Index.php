<?php

namespace App\Livewire\Admin\Accounting\Journals;

use App\Models\JournalEntry;
use App\Domains\Accounting\Actions\JournalAuditor;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public string $search = '';
    public function updatingSearch(): void { $this->resetPage(); }
    public function delete(int $id): void { abort_unless(auth()->user()?->can('journal.delete'), 403); $journal = JournalEntry::findOrFail($id); JournalAuditor::record($journal, 'deleted'); $journal->delete(); session()->flash('status', 'Jurnal berhasil dihapus.'); }
    public function render() { $journals = JournalEntry::with(['debitAccount', 'creditAccount'])->when($this->search !== '', fn ($q) => $q->where('reference', 'like', '%'.$this->search.'%')->orWhere('description', 'like', '%'.$this->search.'%'))->latest('entry_date')->paginate(15); return view('livewire.admin.accounting.journals.index', compact('journals'))->title('Jurnal'); }
}
