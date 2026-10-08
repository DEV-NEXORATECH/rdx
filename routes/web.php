<?php

use App\Livewire\Home\Index;
use App\Livewire\Account\ChangePassword;
use App\Livewire\Account\Profile;
use App\Livewire\Sales\Quotations\Create as CreateOrder;
use App\Livewire\Sales\Quotations\Index as Orders;
use App\Livewire\Sales\Quotations\Edit as EditOrder;
use App\Livewire\MasterData\Index as MasterDataIndex;
use App\Livewire\MasterData\Form as MasterDataForm;
use App\Livewire\Logistics\Jobs\Index as JobsIndex;
use App\Livewire\Logistics\Jobs\Create as JobsCreate;
use App\Livewire\Finance\JobCosts\Index as JobCostsIndex;
use App\Livewire\Finance\JobCosts\Create as JobCostsCreate;
use App\Livewire\Finance\Invoices\Index as InvoicesIndex;
use App\Livewire\Finance\Invoices\Create as InvoicesCreate;
use App\Livewire\Logistics\Jobs\Edit as JobsEdit;
use App\Livewire\Finance\JobCosts\Edit as JobCostsEdit;
use App\Livewire\Finance\Invoices\Edit as InvoicesEdit;
use App\Livewire\Finance\Invoices\Payments as InvoicePayments;
use App\Livewire\Pricing\Tariffs\Index as TariffsIndex;
use App\Livewire\Pricing\Tariffs\Create as TariffsCreate;
use App\Livewire\Pricing\Tariffs\Edit as TariffsEdit;
use App\Livewire\Admin\Accounting\Report as AccountingReport;
use App\Livewire\Admin\Accounting\Accounts\Index as AccountsIndex;
use App\Livewire\Admin\Accounting\Accounts\Create as AccountsCreate;
use App\Livewire\Admin\Accounting\Periods\Index as PeriodsIndex;
use App\Livewire\Admin\Accounting\Periods\Create as PeriodsCreate;
use App\Livewire\Admin\Accounting\Journals\Index as JournalsIndex;
use App\Livewire\Admin\Accounting\Journals\Create as JournalsCreate;
use App\Livewire\Admin\Accounting\Accounts\Edit as AccountsEdit;
use App\Livewire\Admin\Accounting\Periods\Edit as PeriodsEdit;
use App\Livewire\Admin\Accounting\Journals\Edit as JournalsEdit;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\JobOrder;
use App\Models\JobCost;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\PermissionMiddleware;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->intended(route('dashboard'));
    }

    return redirect()->route('login');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', Index::class)->name('dashboard');
    Route::get('account/profile', Profile::class)->name('account.profile');
    Route::get('account/change-password', ChangePassword::class)->name('account.change-password');
    Route::get('sales/quotations', Orders::class)->middleware(PermissionMiddleware::class.':quotation.view')->name('sales.quotations.index');
    Route::get('sales/quotations/create', CreateOrder::class)->middleware(PermissionMiddleware::class.':quotation.create')->name('sales.quotations.create');
    Route::get('sales/quotations/{order}/edit', EditOrder::class)->middleware(PermissionMiddleware::class.':quotation.edit')->name('sales.quotations.edit');
    Route::get('sales/quotations/{order}', function (Order $order) { return view('details.order', compact('order')); })->middleware(PermissionMiddleware::class.':quotation.view')->name('sales.quotations.show');
    Route::get('sales/quotations/export.csv', function (\Illuminate\Http\Request $request) {
        $orders = Order::query()->when($request->date_from, fn ($q) => $q->whereDate('order_date', '>=', $request->date_from))->when($request->date_to, fn ($q) => $q->whereDate('order_date', '<=', $request->date_to))->when($request->customer, fn ($q) => $q->where('customer', $request->customer))->when($request->marketing, fn ($q) => $q->where('marketing', $request->marketing))->when($request->status, fn ($q) => $q->where('status', $request->status))->orderBy('order_date')->get();
        return response()->streamDownload(function () use ($orders) { $out = fopen('php://output', 'w'); fputcsv($out, ['No. Booking', 'Tanggal', 'Status', 'Customer', 'Shipper', 'Consignee', 'Asal', 'Tujuan', 'Marketing', 'Harga Jual', 'Total Modal', 'Laba', 'Persentase Laba']); foreach ($orders as $order) fputcsv($out, [$order->booking_number, $order->order_date->format('Y-m-d'), $order->status, $order->customer, $order->shipper, $order->consignee, $order->origin, $order->destination, $order->marketing, $order->selling_price, $order->total_capital, $order->profit, $order->profit_percentage]); fclose($out); }, 'orders-'.now()->format('Ymd-His').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    })->middleware(PermissionMiddleware::class.':quotation.export')->name('sales.quotations.export.csv');
    Route::get('sales/quotations/export.pdf', function (\Illuminate\Http\Request $request) { $orders = Order::query()->when($request->date_from, fn ($q) => $q->whereDate('order_date', '>=', $request->date_from))->when($request->date_to, fn ($q) => $q->whereDate('order_date', '<=', $request->date_to))->when($request->customer, fn ($q) => $q->where('customer', $request->customer))->when($request->marketing, fn ($q) => $q->where('marketing', $request->marketing))->when($request->status, fn ($q) => $q->where('status', $request->status))->orderBy('order_date')->get(); return view('exports.orders-print', ['orders' => $orders, 'filters' => $request->only(['date_from', 'date_to', 'customer', 'marketing', 'status'])]); })->middleware(PermissionMiddleware::class.':quotation.export')->name('sales.quotations.export.pdf');
    Route::get('logistics/jobs', JobsIndex::class)->middleware(PermissionMiddleware::class.':job.view')->name('logistics.jobs.index');
    Route::get('logistics/jobs/create', JobsCreate::class)->middleware(PermissionMiddleware::class.':job.create')->name('logistics.jobs.create');
    Route::get('logistics/jobs/{jobOrder}/edit', JobsEdit::class)->middleware(PermissionMiddleware::class.':job.edit')->name('logistics.jobs.edit');
    Route::get('logistics/jobs/{jobOrder}', function (JobOrder $jobOrder) { $jobOrder->load(['order', 'costs']); return view('details.job-order', compact('jobOrder')); })->middleware(PermissionMiddleware::class.':job.view')->name('logistics.jobs.show');
    Route::get('finance/job-costs', JobCostsIndex::class)->middleware(PermissionMiddleware::class.':job_cost.view')->name('finance.job-costs.index');
    Route::get('finance/job-costs/create', JobCostsCreate::class)->middleware(PermissionMiddleware::class.':job_cost.create')->name('finance.job-costs.create');
    Route::get('finance/job-costs/{jobCost}/edit', JobCostsEdit::class)->middleware(PermissionMiddleware::class.':job_cost.edit')->name('finance.job-costs.edit');
    Route::get('finance/job-costs/{jobCost}', function (JobCost $jobCost) { $jobCost->load('jobOrder.order'); return view('details.job-cost', compact('jobCost')); })->middleware(PermissionMiddleware::class.':job_cost.view')->name('finance.job-costs.show');
    Route::get('finance/invoices', InvoicesIndex::class)->middleware(PermissionMiddleware::class.':invoice.view')->name('finance.invoices.index');
    Route::get('finance/invoices/create', InvoicesCreate::class)->middleware(PermissionMiddleware::class.':invoice.create')->name('finance.invoices.create');
    Route::get('finance/invoices/{invoice}/edit', InvoicesEdit::class)->middleware(PermissionMiddleware::class.':invoice.edit')->name('finance.invoices.edit');
    Route::get('finance/invoices/{invoice}/payments', InvoicePayments::class)->middleware(PermissionMiddleware::class.':receipt.create')->name('finance.invoices.payments');
    Route::get('finance/invoices/{invoice}', function (Invoice $invoice) { return view('details.invoice', compact('invoice')); })->middleware(PermissionMiddleware::class.':invoice.view')->name('finance.invoices.show');
    Route::get('pricing/tariffs', TariffsIndex::class)->middleware(PermissionMiddleware::class.':tariff.view')->name('pricing.tariffs.index');
    Route::get('pricing/tariffs/create', TariffsCreate::class)->middleware(PermissionMiddleware::class.':tariff.create')->name('pricing.tariffs.create');
    Route::get('pricing/tariffs/{tariff}/edit', TariffsEdit::class)->middleware(PermissionMiddleware::class.':tariff.edit')->name('pricing.tariffs.edit');
    Route::get('accounting/reports', AccountingReport::class)->middleware(PermissionMiddleware::class.':report.view')->name('accounting.reports.index');
    Route::get('accounting/accounts', AccountsIndex::class)->middleware(PermissionMiddleware::class.':account.view')->name('accounting.accounts.index');
    Route::get('accounting/accounts/create', AccountsCreate::class)->middleware(PermissionMiddleware::class.':account.create')->name('accounting.accounts.create');
    Route::get('accounting/accounts/{account}/edit', AccountsEdit::class)->middleware(PermissionMiddleware::class.':account.edit')->name('accounting.accounts.edit');
    Route::get('accounting/periods', PeriodsIndex::class)->middleware(PermissionMiddleware::class.':period.view')->name('accounting.periods.index');
    Route::get('accounting/periods/create', PeriodsCreate::class)->middleware(PermissionMiddleware::class.':period.create')->name('accounting.periods.create');
    Route::get('accounting/periods/{period}/edit', PeriodsEdit::class)->middleware(PermissionMiddleware::class.':period.edit')->name('accounting.periods.edit');
    Route::get('accounting/journals', JournalsIndex::class)->middleware(PermissionMiddleware::class.':journal.view')->name('accounting.journals.index');
    Route::get('accounting/journals/create', JournalsCreate::class)->middleware(PermissionMiddleware::class.':journal.create')->name('accounting.journals.create');
    Route::get('accounting/journals/{journal}/edit', JournalsEdit::class)->middleware(PermissionMiddleware::class.':journal.edit')->name('accounting.journals.edit');
    Route::get('accounting/journals/{journal}', function (JournalEntry $journal) { $journal->load(['debitAccount', 'creditAccount']); return view('details.journal', compact('journal')); })->middleware(PermissionMiddleware::class.':journal.view')->name('accounting.journals.show');

    foreach (['companies', 'locations', 'routes', 'services', 'taxes', 'units', 'customers', 'vendors', 'shippers', 'consignees', 'shipping-lines', 'container-types', 'marketings'] as $masterType) {
        Route::get('master/'.$masterType, MasterDataIndex::class)->name('master.'.$masterType.'.index');
        Route::get('master/'.$masterType.'/create', MasterDataForm::class)->name('master.'.$masterType.'.create');
        Route::get('master/'.$masterType.'/{record}/edit', MasterDataForm::class)->name('master.'.$masterType.'.edit');
    }
    foreach ([['crm', 'customers'], ['crm', 'vendors'], ['fleet', 'vehicles'], ['fleet', 'drivers']] as [$prefix, $masterType]) {
        Route::get($prefix.'/'.$masterType, MasterDataIndex::class)->name($prefix.'.'.$masterType.'.index');
        Route::get($prefix.'/'.$masterType.'/create', MasterDataForm::class)->name($prefix.'.'.$masterType.'.create');
        Route::get($prefix.'/'.$masterType.'/{record}/edit', MasterDataForm::class)->name($prefix.'.'.$masterType.'.edit');
    }

    $modules = [
    ];

    foreach ($modules as $uri => [$name, $title, $description]) {
        Route::get($uri, function () use ($title, $description) {
            return view('modules.placeholder', compact('title', 'description'));
        })->name($name);
    }
});
