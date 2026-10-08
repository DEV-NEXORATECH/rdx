<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['job_order_id', 'invoice_number', 'subtotal', 'ppn_rate', 'pph_rate', 'ppn_amount', 'pph_amount', 'total', 'status', 'paid_at', 'due_date'];
    protected $casts = ['subtotal' => 'decimal:2', 'ppn_rate' => 'decimal:2', 'pph_rate' => 'decimal:2', 'ppn_amount' => 'decimal:2', 'pph_amount' => 'decimal:2', 'total' => 'decimal:2', 'paid_at' => 'datetime', 'due_date' => 'date'];
    public function jobOrder() { return $this->belongsTo(JobOrder::class); }
    public function payments() { return $this->hasMany(InvoicePayment::class); }

    public function getPaidAmountAttribute(): float { return (float) $this->payments()->sum('amount'); }
    public function getRemainingAmountAttribute(): float { return max(0, (float) $this->total - $this->paid_amount); }
}
