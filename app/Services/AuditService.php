<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Log an action in the system.
     *
     * @param string $action The action performed (e.g., 'role.updated')
     * @param string $module The module affected (e.g., 'Roles')
     * @param Model|null $target The model instance being affected (optional)
     * @param array|null $changes Old vs New values (e.g., ['old' => ..., 'new' => ...])
     * @param int|null $userId User ID performing the action (defaults to Auth::id())
     * @return AuditLog
     */
    public function log(string $action, string $module, ?Model $target = null, ?array $changes = null, ?int $userId = null): AuditLog
    {
        return AuditLog::create([
            'user_id' => $userId ?? Auth::id(),
            'action' => $action,
            'module' => $module,
            'target_id' => $target ? $target->getKey() : null,
            'target_type' => $target ? get_class($target) : null,
            'changes' => $changes,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
