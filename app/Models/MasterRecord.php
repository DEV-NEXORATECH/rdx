<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterRecord extends Model
{
    protected $fillable = ['type', 'code', 'name', 'notes', 'metadata', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'metadata' => 'array'];
    }
}
