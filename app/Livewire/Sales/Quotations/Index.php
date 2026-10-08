<?php

namespace App\Livewire\Sales\Quotations;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sort = 'order_date';
    public string $direction = 'desc';
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $customerFilter = '';
    public string $marketingFilter = '';
    public string $statusFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void { $this->resetPage(); }
    public function updatingDateTo(): void { $this->resetPage(); }
    public function updatingCustomerFilter(): void { $this->resetPage(); }
    public function updatingMarketingFilter(): void { $this->resetPage(); }
    public function updatingStatusFilter(): void { $this->resetPage(); }

    public function sortBy(string $column): void
    {
        $allowed = ['booking_number', 'customer', 'order_date'];

        if (! in_array($column, $allowed, true)) {
            return;
        }

        if ($this->sort === $column) {
            $this->direction = $this->direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort = $column;
            $this->direction = 'asc';
        }
    }

    public function render()
    {
        $orders = Order::query()
            ->when($this->search !== '', function ($query): void {
                $term = '%'.$this->search.'%';
                $query->where(fn ($q) => $q->where('booking_number', 'like', $term)->orWhere('customer', 'like', $term));
            })
            ->when($this->dateFrom !== '', fn ($query) => $query->whereDate('order_date', '>=', $this->dateFrom))
            ->when($this->dateTo !== '', fn ($query) => $query->whereDate('order_date', '<=', $this->dateTo))
            ->when($this->customerFilter !== '', fn ($query) => $query->where('customer', $this->customerFilter))
            ->when($this->marketingFilter !== '', fn ($query) => $query->where('marketing', $this->marketingFilter))
            ->when($this->statusFilter !== '', fn ($query) => $query->where('status', $this->statusFilter))
            ->orderBy($this->sort, $this->direction)
            ->paginate(10);

        return view('livewire.sales.quotations.index', ['orders' => $orders, 'customers' => Order::query()->select('customer')->distinct()->orderBy('customer')->pluck('customer'), 'marketings' => Order::query()->select('marketing')->distinct()->orderBy('marketing')->pluck('marketing')])->title('Penawaran & Order');
    }
}
