<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    protected $fillable = ['origin', 'destination', 'shipping_line', 'container_type', 'selling_price', 'capital_price', 'valid_from', 'valid_until', 'is_active'];
    protected function casts(): array { return ['selling_price' => 'decimal:2', 'capital_price' => 'decimal:2', 'valid_from' => 'date', 'valid_until' => 'date', 'is_active' => 'boolean']; }
}
