<?php

namespace App\Livewire\Admin\Accounting;

use App\Models\Invoice;
use App\Models\JobCost;
use App\Models\Order;
use Livewire\Component;

class Report extends Component
{
    public function render()
    {
        return view('livewire.admin.accounting.report', [
            'revenue' => Order::sum('selling_price'),
            'costs' => JobCost::sum('amount'),
            'profit' => Order::sum('profit'),
            'invoiced' => Invoice::sum('total'),
            'paid' => Invoice::where('status', 'paid')->sum('total'),
            'receivable' => Invoice::whereIn('status', ['issued', 'overdue'])->sum('total'),
        ])->title('Laporan Akuntansi');
    }
}
