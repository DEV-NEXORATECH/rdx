<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobOrder extends Model
{
    protected $fillable = ['order_id', 'status', 'scheduled_date', 'vehicle', 'driver', 'notes'];
    protected $casts = ['scheduled_date' => 'date'];
    public function order() { return $this->belongsTo(Order::class); }
    public function costs() { return $this->hasMany(JobCost::class); }
}
