<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'booking_number', 'tariff_id', 'order_date', 'origin', 'shipper', 'consignee', 'customer',
        'destination', 'shipping_line', 'container_type', 'marketing', 'status',
        'selling_price', 'capital_door', 'capital_of', 'capital_ops', 'capital_other',
        'ppn_rate', 'pph_rate', 'ppn_amount', 'pph_amount', 'profit', 'profit_percentage',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'date',
            'selling_price' => 'decimal:2',
            'capital_door' => 'decimal:2',
            'capital_of' => 'decimal:2',
            'capital_ops' => 'decimal:2',
            'capital_other' => 'decimal:2',
            'ppn_rate' => 'decimal:2',
            'pph_rate' => 'decimal:2',
            'ppn_amount' => 'decimal:2',
            'pph_amount' => 'decimal:2',
            'profit' => 'decimal:2',
            'profit_percentage' => 'decimal:2',
        ];
    }

    public function jobOrder()
    {
        return $this->hasOne(JobOrder::class);
    }

    public function tariff()
    {
        return $this->belongsTo(Tariff::class);
    }

    public function getTotalCapitalAttribute(): float
    {
        return (float) $this->capital_door + (float) $this->capital_of
            + (float) $this->capital_ops + (float) $this->capital_other;
    }
}
