<?php

namespace App\Services;

use App\Models\User;

class WorkspaceContextService
{
    private $dashboardService;
    private $navigationService;
    private $policyEngine;

    public function __construct(DashboardService $dashboardService, MobileNavigationService $navigationService, PolicyEngine $policyEngine)
    {
        $this->dashboardService = $dashboardService;
        $this->navigationService = $navigationService;
        $this->policyEngine = $policyEngine;
    }

    /**
     * Get the full context for a specific workspace switch or app load.
     */
    public function getContext(User $user, $workspaceId)
    {
        if (!$this->policyEngine->canAccessWorkspace($user, $workspaceId)) {
            throw new \Exception('Unauthorized access to workspace.');
        }

        return [
            'workspace_id' => $workspaceId,
            'navigation' => $this->navigationService->buildNavigation($user, $workspaceId),
            'dashboard' => $this->dashboardService->getWidgets($user, $workspaceId),
            // Sync status hash allows mobile to check if permissions have changed
            'sync_hash' => md5(json_encode([
                $this->navigationService->buildNavigation($user, $workspaceId),
                $this->dashboardService->getWidgets($user, $workspaceId)
            ]))
        ];
    }
}
