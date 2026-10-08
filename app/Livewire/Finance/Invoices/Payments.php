<?php

namespace App\Livewire\Finance\Invoices;

use App\Domains\Accounting\Actions\RecordJournal;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Payments extends Component
{
    public Invoice $invoice;
    public float $amount = 0;
    public string $paid_on = '';
    public string $method = 'transfer';
    public string $reference = '';
    public string $notes = '';

    public function mount(Invoice $invoice): void
    {
        $this->invoice = $invoice;
        $this->paid_on = Carbon::today()->format('Y-m-d');
        $this->amount = $invoice->remaining_amount;
    }

    public function save(): void
    {
        $this->validate(['amount' => ['required', 'numeric', 'gt:0', 'lte:'.$this->invoice->remaining_amount], 'paid_on' => ['required', 'date'], 'method' => ['required', 'in:cash,transfer,giro,other'], 'reference' => ['nullable', 'string', 'max:100'], 'notes' => ['nullable', 'string', 'max:1000']]);

        $payment = InvoicePayment::create(['invoice_id' => $this->invoice->id, 'amount' => $this->amount, 'paid_on' => $this->paid_on, 'method' => $this->method, 'reference' => $this->reference ?: null, 'notes' => $this->notes ?: null]);
        RecordJournal::create($this->paid_on, 'PAY-'.$payment->id, 'Pembayaran '.$this->invoice->invoice_number, '1000', '1100', $this->amount);

        $this->invoice->refresh();
        if ($this->invoice->remaining_amount <= 0) $this->invoice->update(['status' => 'paid', 'paid_at' => now()]);
        elseif ($this->invoice->status === 'draft') $this->invoice->update(['status' => 'issued']);

        session()->flash('status', 'Pembayaran berhasil dicatat.');
        $this->redirectRoute('finance.invoices.payments', $this->invoice);
    }

    public function render()
    {
        return view('livewire.finance.invoices.payments', ['payments' => $this->invoice->payments()->latest('paid_on')->get()])->title('Pembayaran Tagihan');
    }
}
