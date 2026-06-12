<?php

namespace App\Services;

use App\Models\User;

class AccessSyncService
{
    private $workspaceContextService;

    public function __construct(WorkspaceContextService $workspaceContextService)
    {
        $this->workspaceContextService = $workspaceContextService;
    }

    /**
     * Called by mobile app on resume, push notification, or token refresh to get real-time sync.
     */
    public function sync(User $user, $currentWorkspaceId)
    {
        if (!$user->is_active) {
            return [
                'status' => 'inactive',
                'action' => 'force_logout'
            ];
        }

        try {
            $context = $this->workspaceContextService->getContext($user, $currentWorkspaceId);
            
            return [
                'status' => 'active',
                'action' => 'update_context',
                'context' => $context
            ];
        } catch (\Exception $e) {
            // Usually means access to current workspace revoked
            return [
                'status' => 'active',
                'action' => 'workspace_revoked'
            ];
        }
    }
}
