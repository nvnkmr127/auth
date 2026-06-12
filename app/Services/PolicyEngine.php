<?php

namespace App\Services;

use App\Models\User;

class PolicyEngine
{
    /**
     * Centralized policy validation.
     */
    public function canAccessWorkspace(User $user, $workspaceId): bool
    {
        if (!$user->is_active) return false;
        if ($user->isAdmin()) return true;
        
        return $user->appAccesses()->where('app_id', $workspaceId)->exists();
    }

    public function canAccessFeature(User $user, $workspaceId, string $featureKey): bool
    {
        if (!$user->is_active) return false;
        if ($user->isAdmin()) return true;

        return $user->hasPermission($featureKey, $workspaceId);
    }
}
