<?php

namespace App\Domains\Shared\Actions;

use App\Domains\Admin\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

final class RecordAudit
{
    public static function record(
        Model $auditable,
        string $event,
        string $summary,
        ?array $properties = null,
    ): AuditLog {
        $properties ??= $auditable->getAttributes();

        if (array_key_exists('password', $properties) || array_key_exists('remember_token', $properties)) {
            $properties = array_map(fn ($value, $key) => in_array($key, ['password', 'remember_token'], true) ? '[redacted]' : $value, $properties, array_keys($properties));
        }

        return AuditLog::create([
            'actor_user_id' => Auth::id(),
            'user_type' => Auth::user()?->getMorphClass() ?? 'guest',
            'event' => $event,
            'auditable_type' => $auditable->getMorphClass(),
            'auditable_id' => $auditable->getKey(),
            'summary' => $summary,
            'properties' => $properties,
            'ip_address' => Request::ip(),
            'user_agent' => mb_substr(Request::userAgent() ?? '', 0, 255),
        ]);
    }

    public static function instance(string $event, string $summary, ?array $properties = null, ?string $actorUserId = null): void
    {
        AuditLog::create([
            'actor_user_id' => $actorUserId ?? Auth::id(),
            'user_type' => Auth::user()?->getMorphClass() ?? 'guest',
            'event' => $event,
            'auditable_type' => null,
            'auditable_id' => null,
            'summary' => $summary,
            'properties' => $properties,
            'ip_address' => Request::ip(),
            'user_agent' => mb_substr(Request::userAgent() ?? '', 0, 255),
        ]);
    }
}
