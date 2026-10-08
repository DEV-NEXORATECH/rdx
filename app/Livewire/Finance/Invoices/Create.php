<?php

namespace App\Livewire\Finance\Invoices;

use App\Domains\Accounting\Actions\RecordJournal;
use App\Domains\Shared\Support\DocumentNumber;
use App\Models\Invoice;
use App\Models\JobOrder;
use Livewire\Component;

class Create extends Component
{
    public ?int $job_order_id = null;
    public float $subtotal = 0;
    public ?float $ppn_rate = null;
    public ?float $pph_rate = null;
    public string $status = 'draft';
    public string $due_date = '';

    public function getPpnAmountProperty(): float { return $this->ppn_rate === null ? 0 : $this->subtotal * $this->ppn_rate / 100; }
    public function getPphAmountProperty(): float { return $this->pph_rate === null ? 0 : $this->subtotal * $this->pph_rate / 100; }
    public function getTotalProperty(): float { return $this->subtotal + $this->ppnAmount - $this->pphAmount; }

    public function save(): void
    {
        $this->validate(['job_order_id' => ['required', 'exists:job_orders,id'], 'subtotal' => ['required', 'numeric', 'min:0'], 'ppn_rate' => ['nullable', 'numeric', 'min:0', 'max:100'], 'pph_rate' => ['nullable', 'numeric', 'min:0', 'max:100'], 'status' => ['required', 'in:draft,issued,paid,overdue'], 'due_date' => ['nullable', 'date']]);
        $invoice = Invoice::create(['job_order_id' => $this->job_order_id, 'invoice_number' => DocumentNumber::next('INVOICE', 'INV/{year}/{seq:5}'), 'subtotal' => $this->subtotal, 'ppn_rate' => $this->ppn_rate, 'pph_rate' => $this->pph_rate, 'ppn_amount' => $this->ppnAmount, 'pph_amount' => $this->pphAmount, 'total' => $this->total, 'status' => $this->status, 'due_date' => $this->due_date ?: null]);
        RecordJournal::create(now()->toDateString(), $invoice->invoice_number, 'Tagihan '.$invoice->invoice_number, '1100', '4100', $this->subtotal);
        if ($this->status === 'paid') {
            RecordJournal::create(now()->toDateString(), $invoice->invoice_number, 'Pelunasan '.$invoice->invoice_number, '1000', '1100', $this->total);
            $invoice->update(['paid_at' => now()]);
        }
        session()->flash('status', 'Tagihan berhasil dibuat.');
        $this->redirectRoute('finance.invoices.index');
    }

    public function render()
    {
        return view('livewire.finance.invoices.create', ['jobs' => JobOrder::with('order')->latest()->get()])->title('Buat Tagihan');
    }
}
