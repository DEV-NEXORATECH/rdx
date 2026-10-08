<?php

namespace App\Livewire\Logistics\Jobs;

use App\Models\JobOrder;
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
        if (! in_array($column, ['created_at', 'status'], true)) return;
        if ($this->sort === $column) $this->direction = $this->direction === 'asc' ? 'desc' : 'asc';
        else [$this->sort, $this->direction] = [$column, 'asc'];
    }

    public function delete(int $id): void
    {
        abort_unless(auth()->user()?->can('job.delete'), 403);
        JobOrder::findOrFail($id)->delete();
        session()->flash('status', 'Job Order berhasil dihapus.');
    }

    public function render()
    {
        $jobs = JobOrder::with('order')->when($this->status !== '', fn ($q) => $q->where('status', $this->status))->when($this->search !== '', fn ($q) => $q->whereHas('order', fn ($o) => $o->where('booking_number', 'like', '%'.$this->search.'%')->orWhere('customer', 'like', '%'.$this->search.'%')))->orderBy($this->sort, $this->direction)->paginate(10);
        return view('livewire.logistics.jobs.index', compact('jobs'))->title('Job Order');
    }
}
