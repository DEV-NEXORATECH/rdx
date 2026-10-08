<?php

namespace App\Livewire\Home;

use App\Models\Invoice;
use App\Models\JobOrder;
use App\Models\Order;
use App\Models\JobCost;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $months = collect(range(5, 0))->map(function (int $offset): array {
            $month = Carbon::now()->startOfMonth()->subMonths($offset);
            $nextMonth = $month->copy()->addMonth();

            $revenue = (float) Order::whereBetween('order_date', [$month, $nextMonth->copy()->subSecond()])->sum('selling_price');
            $costs = (float) JobCost::whereBetween('created_at', [$month, $nextMonth->copy()->subSecond()])->sum('amount');

            return [
                'label' => $month->translatedFormat('M'),
                'revenue' => $revenue,
                'costs' => $costs,
                'profit' => $revenue - $costs,
                'orders' => Order::whereBetween('order_date', [$month, $nextMonth->copy()->subSecond()])->count(),
            ];
        })->values()->all();

        $orderStatuses = Order::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();

        return view('livewire.home.index', [
            'activeJobs' => JobOrder::whereIn('status', ['draft', 'scheduled', 'in_progress'])->count(),
            'orders' => Order::count(),
            'openInvoices' => Invoice::whereIn('status', ['draft', 'issued', 'overdue'])->sum('total'),
            'totalCosts' => JobCost::sum('amount'),
            'totalRevenue' => Order::sum('selling_price'),
            'totalProfit' => Order::sum('profit'),
            'months' => $months,
            'orderStatuses' => $orderStatuses,
            'recentOrders' => Order::latest('order_date')->take(5)->get(),
        ])->title('Dashboard');
    }
}
