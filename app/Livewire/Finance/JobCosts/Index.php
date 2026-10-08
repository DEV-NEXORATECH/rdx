<?php

namespace App\Livewire\Finance\JobCosts;

use App\Models\JobCost;
use App\Models\JournalEntry;
use App\Domains\Accounting\Actions\JournalAuditor;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public string $search = '';
    public string $sort = 'created_at';
    public string $direction = 'desc';
    public function updatingSearch(): void { $this->resetPage(); }
    public function sortBy(string $column): void
    {
        if (! in_array($column, ['created_at', 'amount'], true)) return;
        if ($this->sort === $column) $this->direction = $this->direction === 'asc' ? 'desc' : 'asc';
        else [$this->sort, $this->direction] = [$column, 'asc'];
    }
    public function delete(int $id): void
    {
        abort_unless(auth()->user()?->can('job_cost.delete'), 403);
        $cost = JobCost::findOrFail($id);
        if ($journal = JournalEntry::where('reference', 'COST-'.$cost->id)->first()) JournalAuditor::reverse($journal, 'Penghapusan biaya '.$cost->id);
        $cost->delete();
        session()->flash('status', 'Biaya berhasil dihapus.');
    }
    public function render()
    {
        $costs = JobCost::with('jobOrder.order')->when($this->search !== '', fn ($q) => $q->where('category', 'like', '%'.$this->search.'%')->orWhereHas('jobOrder.order', fn ($o) => $o->where('booking_number', 'like', '%'.$this->search.'%')))->orderBy($this->sort, $this->direction)->paginate(10);
        return view('livewire.finance.job-costs.index', compact('costs'))->title('Biaya Job');
    }
}
