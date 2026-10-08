<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCost extends Model
{
    protected $fillable = ['job_order_id', 'category', 'amount', 'notes'];
    protected $casts = ['amount' => 'decimal:2'];
    public function jobOrder() { return $this->belongsTo(JobOrder::class); }
}
