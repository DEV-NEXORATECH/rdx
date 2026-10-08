<?php

namespace App\Livewire\Admin\Accounting\Journals;

use App\Models\Account;
use App\Models\JournalEntry;
use App\Domains\Accounting\Actions\JournalAuditor;

class Edit extends Create
{
    public ?int $editingId = null;
    public function mount(?JournalEntry $journal = null): void { abort_if($journal === null, 404); $this->editingId = $journal->id; $this->entry_date = $journal->entry_date->format('Y-m-d'); $this->reference = (string) $journal->reference; $this->description = $journal->description; $this->debit_account_id = $journal->debit_account_id; $this->credit_account_id = $journal->credit_account_id; $this->amount = (float) $journal->amount; }
    public function save(): void { $this->validate(['entry_date' => ['required', 'date'], 'reference' => ['nullable', 'string', 'max:100'], 'description' => ['required', 'string', 'max:255'], 'debit_account_id' => ['required', 'different:credit_account_id', 'exists:accounts,id'], 'credit_account_id' => ['required', 'exists:accounts,id'], 'amount' => ['required', 'numeric', 'gt:0']]); $journal = JournalEntry::findOrFail($this->editingId); JournalAuditor::record($journal, 'updated'); $journal->update($this->only(['entry_date', 'reference', 'description', 'debit_account_id', 'credit_account_id', 'amount'])); JournalAuditor::record($journal->fresh(), 'updated'); session()->flash('status', 'Jurnal berhasil diperbarui.'); $this->redirectRoute('accounting.journals.index'); }
    public function render() { return view('livewire.admin.accounting.journals.edit', ['accounts' => Account::where('is_active', true)->orderBy('code')->get()])->title('Edit Jurnal'); }
}
