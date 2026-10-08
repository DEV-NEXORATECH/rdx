<?php

namespace App\Livewire\Admin\Audits;

use App\Models\JournalAudit;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $action = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingAction(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $audits = JournalAudit::query()
            ->with(['journalEntry.debitAccount', 'journalEntry.creditAccount'])
            ->when($this->action !== '', fn ($query) => $query->where('action', $this->action))
            ->when($this->search !== '', function ($query) {
                $term = '%'.$this->search.'%';

                $query->where(function ($nested) use ($term) {
                    $nested->where('source_type', 'like', $term)
                        ->orWhere('source_id', 'like', $term)
                        ->orWhereHas('journalEntry', fn ($journal) => $journal
                            ->where('reference', 'like', $term)
                            ->orWhere('description', 'like', $term));
                });
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.audits.index', compact('audits'))->title('Jejak Audit');
    }
}
