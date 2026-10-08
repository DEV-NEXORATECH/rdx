<?php

namespace App\Domains\Admin\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditLog extends Model
{
    protected $fillable = [
        'actor_user_id',
        'user_type',
        'event',
        'auditable_type',
        'auditable_id',
        'summary',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function actor()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'actor_user_id');
    }

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeForEntity(Builder $query, Model $entity): Builder
    {
        return $query->where('auditable_type', $entity->getMorphClass())
            ->where('auditable_id', $entity->getKey());
    }
}
