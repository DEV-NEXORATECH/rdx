<?php

namespace App\Livewire\Admin\Accounting\Periods;

use App\Models\AccountingPeriod;
use Livewire\Component;

class Create extends Component
{
    public string $name = ''; public string $starts_on = ''; public string $ends_on = ''; public string $status = 'open';
    public function save(): void { $this->validate(['name' => ['required', 'string', 'max:100'], 'starts_on' => ['required', 'date'], 'ends_on' => ['required', 'date', 'after_or_equal:starts_on'], 'status' => ['required', 'in:open,closed']]); AccountingPeriod::create($this->only(['name', 'starts_on', 'ends_on', 'status'])); session()->flash('status', 'Periode berhasil disimpan.'); $this->redirectRoute('accounting.periods.index'); }
    public function render() { return view('livewire.admin.accounting.periods.create')->title('Tambah Periode'); }
}
