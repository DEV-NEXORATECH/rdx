<?php

namespace App\Livewire\Finance\JobCosts;

use App\Domains\Accounting\Actions\RecordJournal;
use App\Models\JobCost;
use App\Models\JobOrder;
use Livewire\Component;

class Create extends Component
{
    public ?int $job_order_id = null;
    public string $category = 'Operasional';
    public float $amount = 0;
    public string $notes = '';

    public function save(): void
    {
        $this->validate(['job_order_id' => ['required', 'exists:job_orders,id'], 'category' => ['required', 'string', 'max:100'], 'amount' => ['required', 'numeric', 'min:0'], 'notes' => ['nullable', 'string']]);
        $cost = JobCost::create($this->only(['job_order_id', 'category', 'amount', 'notes']));
        RecordJournal::create(now()->toDateString(), 'COST-'.$cost->id, 'Biaya '.$this->category, '6100', '2100', $this->amount);
        session()->flash('status', 'Biaya berhasil disimpan.');
        $this->redirectRoute('finance.job-costs.index');
    }

    public function render()
    {
        return view('livewire.finance.job-costs.create', ['jobs' => JobOrder::with('order')->latest()->get()])->title('Tambah Biaya Job');
    }
}
