<?php

namespace App\Livewire\Admin\Accounting\Accounts;

use App\Models\Account;

class Edit extends Create
{
    public ?int $editingId = null;
    public function mount(Account $account): void { $this->editingId = $account->id; $this->code = $account->code; $this->name = $account->name; $this->type = $account->type; $this->is_active = $account->is_active; }
    public function save(): void { $this->validate(['code' => ['required', 'string', 'max:30', 'unique:accounts,code,'.$this->editingId], 'name' => ['required', 'string', 'max:255'], 'type' => ['required', 'in:asset,liability,equity,revenue,expense']]); Account::findOrFail($this->editingId)->update($this->only(['code', 'name', 'type', 'is_active'])); session()->flash('status', 'Akun berhasil diperbarui.'); $this->redirectRoute('accounting.accounts.index'); }
    public function render() { return view('livewire.admin.accounting.accounts.edit')->title('Edit Akun'); }
}
