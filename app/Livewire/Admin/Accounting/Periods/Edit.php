<?php

namespace App\Livewire\Admin\Accounting\Periods;

use App\Models\AccountingPeriod;

class Edit extends Create
{
    public ?int $editingId = null;
    public function mount(AccountingPeriod $period): void { $this->editingId = $period->id; $this->name = $period->name; $this->starts_on = $period->starts_on->format('Y-m-d'); $this->ends_on = $period->ends_on->format('Y-m-d'); $this->status = $period->status; }
    public function save(): void { $this->validate(['name' => ['required', 'string', 'max:100'], 'starts_on' => ['required', 'date'], 'ends_on' => ['required', 'date', 'after_or_equal:starts_on'], 'status' => ['required', 'in:open,closed']]); AccountingPeriod::findOrFail($this->editingId)->update($this->only(['name', 'starts_on', 'ends_on', 'status'])); session()->flash('status', 'Periode berhasil diperbarui.'); $this->redirectRoute('accounting.periods.index'); }
    public function render() { return view('livewire.admin.accounting.periods.edit')->title('Edit Periode'); }
}
