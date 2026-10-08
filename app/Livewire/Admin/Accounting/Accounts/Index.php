<?php

namespace App\Livewire\Admin\Accounting\Accounts;

use App\Models\Account;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public string $search = ''; public string $type = '';
    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingType(): void { $this->resetPage(); }
    public function delete(int $id): void { abort_unless(auth()->user()?->can('account.delete'), 403); Account::findOrFail($id)->delete(); session()->flash('status', 'Akun berhasil dihapus.'); }
    public function render() { $accounts = Account::when($this->search !== '', fn ($q) => $q->where('code', 'like', '%'.$this->search.'%')->orWhere('name', 'like', '%'.$this->search.'%'))->when($this->type !== '', fn ($q) => $q->where('type', $this->type))->orderBy('code')->paginate(15); return view('livewire.admin.accounting.accounts.index', compact('accounts'))->title('Bagan Akun'); }
}
