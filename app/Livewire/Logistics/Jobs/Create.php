<?php

namespace App\Livewire\Logistics\Jobs;

use App\Models\JobOrder;
use App\Models\MasterRecord;
use App\Models\Order;
use Livewire\Component;

class Create extends Component
{
    public ?int $order_id = null;
    public string $status = 'draft';
    public string $scheduled_date = '';
    public string $vehicle = '';
    public string $driver = '';
    public string $notes = '';

    public function save(): void
    {
        $this->validate(['order_id' => ['required', 'exists:orders,id'], 'scheduled_date' => ['nullable', 'date'], 'vehicle' => ['nullable', 'string', 'max:255'], 'driver' => ['nullable', 'string', 'max:255'], 'status' => ['required', 'in:draft,scheduled,in_progress,completed,cancelled']]);
        JobOrder::create($this->only(['order_id', 'status', 'scheduled_date', 'vehicle', 'driver', 'notes']));
        session()->flash('status', 'Job Order berhasil dibuat.');
        $this->redirectRoute('logistics.jobs.index');
    }

    public function render()
    {
        return view('livewire.logistics.jobs.create', ['orders' => Order::doesntHave('jobOrder')->latest()->get(), 'vehicles' => MasterRecord::where('type', 'vehicles')->where('is_active', true)->orderBy('name')->get(), 'drivers' => MasterRecord::where('type', 'drivers')->where('is_active', true)->orderBy('name')->get()])->title('Buat Job Order');
    }
}
