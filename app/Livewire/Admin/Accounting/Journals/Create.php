<?php

namespace App\Livewire\Admin\Accounting\Journals;

use App\Models\Account;
use App\Models\JournalEntry;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Create extends Component
{
    public string $entry_date = ''; public string $reference = ''; public string $description = '';
    public ?int $debit_account_id = null; public ?int $credit_account_id = null; public float $amount = 0;
    public function mount(?JournalEntry $journal = null): void { $this->entry_date = Carbon::today()->format('Y-m-d'); }
    public function save(): void
    {
        $this->validate(['entry_date' => ['required', 'date'], 'reference' => ['nullable', 'string', 'max:100'], 'description' => ['required', 'string', 'max:255'], 'debit_account_id' => ['required', 'different:credit_account_id', 'exists:accounts,id'], 'credit_account_id' => ['required', 'exists:accounts,id'], 'amount' => ['required', 'numeric', 'gt:0']]);
        JournalEntry::create($this->only(['entry_date', 'reference', 'description', 'debit_account_id', 'credit_account_id', 'amount']));
        session()->flash('status', 'Jurnal berhasil disimpan.'); $this->redirectRoute('accounting.journals.index');
    }
    public function render() { return view('livewire.admin.accounting.journals.create', ['accounts' => Account::where('is_active', true)->orderBy('code')->get()])->title('Tambah Jurnal'); }
}
