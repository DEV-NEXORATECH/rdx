<?php

namespace App\Livewire\Concerns;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait WithListing
{
    public string $search = '';

    public string $sort = '';

    public string $direction = 'asc';

    public int $perPage = 10;

    public bool $showFilters = false;

    protected function defaultSort(): string
    {
        return 'id';
    }

    protected function defaultDirection(): string
    {
        return 'asc';
    }

    public function mountWithListing(): void
    {
        $this->sort = $this->sort ?: $this->defaultSort();
        $this->direction = $this->direction ?: $this->defaultDirection();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function toggleFilters(): void
    {
        $this->showFilters = ! $this->showFilters;
    }

    public function sortBy(string $key): void
    {
        if ($this->sort === $key) {
            $this->direction = $this->direction === 'asc' ? 'desc' : 'asc';

            return;
        }

        $this->sort = $key;
        $this->direction = $this->defaultDirection();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'showFilters']);
    }

    public function resetPage(): void
    {
        $this->dispatch('resetPage');
    }

    protected function applySort(Builder $query, ?string $defaultSort = null, ?string $defaultDirection = null): Builder
    {
        $sort = $this->sort ?: ($defaultSort ?: $this->defaultSort());
        $direction = $this->direction ?: ($defaultDirection ?: $this->defaultDirection());

        return $query->orderBy($sort, $direction);
    }

    protected function applySearch(Builder $query, array $columns): Builder
    {
        $term = trim($this->search);

        if ($term === '') {
            return $query;
        }

        return $query->where(function (Builder $q) use ($columns, $term): void {
            foreach ($columns as $index => $column) {
                if ($index === 0) {
                    $q->where($column, 'like', '%'.$term.'%');

                    continue;
                }

                $q->orWhere($column, 'like', '%'.$term.'%');
            }
        });
    }

    /**
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     * @return Paginator|Collection<int, TModel>
     */
    protected function paginateQuery(Builder $query, ?int $perPage = null): Paginator|Collection
    {
        $limit = $perPage ?: $this->perPage;

        if ($limit < 0) {
            return $query->get();
        }

        return $query->paginate($limit);
    }
}
