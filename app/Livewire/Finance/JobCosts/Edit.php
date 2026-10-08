<?php

namespace App\Livewire\Finance\JobCosts;

use App\Models\JobCost;
use App\Models\JobOrder;
use App\Models\JournalAudit;
use App\Models\JournalEntry;
use App\Domains\Accounting\Actions\JournalAuditor;
use App\Domains\Accounting\Actions\RecordJournal;

class Edit extends Create
{
    public ?int $editingId = null;

    public function mount(JobCost $jobCost): void
    {
        $this->editingId = $jobCost->id;
        $this->job_order_id = $jobCost->job_order_id;
        $this->category = $jobCost->category;
        $this->amount = (float) $jobCost->amount;
        $this->notes = (string) $jobCost->notes;
    }
    public function save(): void
    {
        $this->validate(['job_order_id' => ['required', 'exists:job_orders,id'], 'category' => ['required', 'string', 'max:100'], 'amount' => ['required', 'numeric', 'min:0'], 'notes' => ['nullable', 'string']]);
        $cost = JobCost::findOrFail($this->editingId);
        JournalAudit::create(['source_type' => 'job_cost', 'source_id' => $cost->id, 'action' => 'updated', 'snapshot' => $cost->toArray()]);
        if ($journal = JournalEntry::where('reference', 'COST-'.$cost->id)->first()) {
            JournalAuditor::reverse($journal, 'Perubahan biaya '.$cost->id);
        }
        $cost->update($this->only(['job_order_id', 'category', 'amount', 'notes']));
        RecordJournal::create(now()->toDateString(), 'COST-'.$cost->id.'-EDIT-'.now()->timestamp, 'Biaya '.$cost->category, '6100', '2100', $cost->amount);
        session()->flash('status', 'Biaya berhasil diperbarui.');
        $this->redirectRoute('finance.job-costs.index');
    }
    public function render() { return view('livewire.finance.job-costs.edit', ['jobs' => JobOrder::with('order')->latest()->get()])->title('Edit Biaya Job'); }
}
