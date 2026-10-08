<?php

namespace App\Livewire\Logistics\Jobs;

use App\Models\JobOrder;
use App\Models\MasterRecord;

class Edit extends Create
{
    public ?int $editingId = null;

    public function mount(JobOrder $jobOrder): void
    {
        $this->editingId = $jobOrder->id;
        $this->order_id = $jobOrder->order_id;
        $this->status = $jobOrder->status;
        $this->scheduled_date = $jobOrder->scheduled_date?->format('Y-m-d') ?? '';
        $this->vehicle = (string) $jobOrder->vehicle;
        $this->driver = (string) $jobOrder->driver;
        $this->notes = (string) $jobOrder->notes;
    }

    public function save(): void
    {
        $this->validate(['order_id' => ['required', 'exists:orders,id'], 'scheduled_date' => ['nullable', 'date'], 'vehicle' => ['nullable', 'string', 'max:255'], 'driver' => ['nullable', 'string', 'max:255'], 'status' => ['required', 'in:draft,scheduled,in_progress,completed,cancelled']]);
        JobOrder::findOrFail($this->editingId)->update($this->only(['order_id', 'status', 'scheduled_date', 'vehicle', 'driver', 'notes']));
        session()->flash('status', 'Job Order berhasil diperbarui.');
        $this->redirectRoute('logistics.jobs.index');
    }

    public function render()
    {
        return view('livewire.logistics.jobs.edit', ['orders' => \App\Models\Order::latest()->get(), 'vehicles' => MasterRecord::where('type', 'vehicles')->where('is_active', true)->orderBy('name')->get(), 'drivers' => MasterRecord::where('type', 'drivers')->where('is_active', true)->orderBy('name')->get()])->title('Edit Job Order');
    }
}
