<?php

namespace App\Livewire\Finance\Invoices;

use App\Models\Invoice;
use App\Models\JobOrder;
use App\Models\JournalAudit;
use App\Models\JournalEntry;
use App\Domains\Accounting\Actions\JournalAuditor;
use App\Domains\Accounting\Actions\RecordJournal;

class Edit extends Create
{
    public ?int $editingId = null;

    public function mount(Invoice $invoice): void
    {
        $this->editingId = $invoice->id;
        $this->job_order_id = $invoice->job_order_id;
        $this->subtotal = (float) $invoice->subtotal;
        $this->ppn_rate = $invoice->ppn_rate === null ? null : (float) $invoice->ppn_rate;
        $this->pph_rate = $invoice->pph_rate === null ? null : (float) $invoice->pph_rate;
        $this->status = $invoice->status;
        $this->due_date = $invoice->due_date?->format('Y-m-d') ?? '';
    }
    public function save(): void
    {
        $this->validate(['job_order_id' => ['required', 'exists:job_orders,id'], 'subtotal' => ['required', 'numeric', 'min:0'], 'ppn_rate' => ['nullable', 'numeric', 'min:0', 'max:100'], 'pph_rate' => ['nullable', 'numeric', 'min:0', 'max:100'], 'status' => ['required', 'in:draft,issued,paid,overdue'], 'due_date' => ['nullable', 'date']]);
        $invoice = Invoice::findOrFail($this->editingId);
        JournalAudit::create(['source_type' => 'invoice', 'source_id' => $invoice->id, 'action' => 'updated', 'snapshot' => $invoice->toArray()]);
        if ($journal = JournalEntry::where('reference', $invoice->invoice_number)->first()) {
            JournalAuditor::reverse($journal, 'Perubahan tagihan '.$invoice->invoice_number);
        }
        $wasPaid = $invoice->status === 'paid';
        $invoice->update(['job_order_id' => $this->job_order_id, 'subtotal' => $this->subtotal, 'ppn_rate' => $this->ppn_rate, 'pph_rate' => $this->pph_rate, 'ppn_amount' => $this->ppnAmount, 'pph_amount' => $this->pphAmount, 'total' => $this->total, 'status' => $this->status, 'paid_at' => $this->status === 'paid' ? ($invoice->paid_at ?: now()) : null, 'due_date' => $this->due_date ?: null]);
        if ($this->status !== 'draft') {
            RecordJournal::create(now()->toDateString(), $invoice->invoice_number.'-EDIT-'.now()->timestamp, 'Tagihan '.$invoice->invoice_number, '1100', '4100', $this->total);
        }
        if ($this->status === 'paid' && ! $wasPaid) {
            RecordJournal::create(now()->toDateString(), $invoice->invoice_number, 'Pelunasan '.$invoice->invoice_number, '1000', '1100', $this->total);
        }
        session()->flash('status', 'Tagihan berhasil diperbarui.');
        $this->redirectRoute('finance.invoices.index');
    }
    public function render() { return view('livewire.finance.invoices.edit', ['jobs' => JobOrder::with('order')->latest()->get()])->title('Edit Tagihan'); }
}
