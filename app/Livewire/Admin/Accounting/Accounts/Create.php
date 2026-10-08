<?php

namespace App\Livewire\Admin\Accounting\Accounts;

use App\Models\Account;
use Livewire\Component;

class Create extends Component
{
    public string $code = ''; public string $name = ''; public string $type = 'asset'; public bool $is_active = true;
    public function save(): void { $this->validate(['code' => ['required', 'string', 'max:30', 'unique:accounts,code'], 'name' => ['required', 'string', 'max:255'], 'type' => ['required', 'in:asset,liability,equity,revenue,expense']]); Account::create($this->only(['code', 'name', 'type', 'is_active'])); session()->flash('status', 'Akun berhasil disimpan.'); $this->redirectRoute('accounting.accounts.index'); }
    public function render() { return view('livewire.admin.accounting.accounts.create')->title('Tambah Akun'); }
}
