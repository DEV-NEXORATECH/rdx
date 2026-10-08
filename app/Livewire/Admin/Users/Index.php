<?php

namespace App\Livewire\Admin\Users;

use App\Domains\Identity\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public string $role = '';

    public string $status = '';

    public string $sort = 'name';

    public string $direction = 'asc';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRole(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $key): void
    {
        if ($this->sort === $key) {
            $this->direction = $this->direction === 'asc' ? 'desc' : 'asc';

            return;
        }

        $this->sort = $key;
        $this->direction = 'asc';
    }

    public function render()
    {
        $query = User::query()
            ->with('roles')
            ->when($this->search !== '', function (Builder $builder): void {
                $term = '%'.$this->search.'%';
                $builder->where(function (Builder $inner) use ($term): void {
                    $inner->where('name', 'like', $term)
                        ->orWhere('email', 'like', $term);
                });
            })
            ->when($this->role !== '', function (Builder $builder): void {
                $builder->whereHas('roles', fn (Builder $roleQuery) => $roleQuery->where('name', $this->role));
            })
            ->when($this->status !== '', function (Builder $builder): void {
                $builder->where('is_active', $this->status === 'active');
            })
            ->orderBy($this->sort, $this->direction);

        $users = $query->paginate(10);

        return view('livewire.admin.users.index', [
            'users' => $users,
            'roles' => Role::query()->orderBy('name')->pluck('name'),
        ])->title('Manajemen Pengguna');
    }
}
