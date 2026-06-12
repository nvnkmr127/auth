<?php

namespace App\Services;

use App\Models\User;

class MobileNavigationService
{
    private $policyEngine;
    private $appRegistryService;

    public function __construct(PolicyEngine $policyEngine, AppRegistryService $appRegistryService)
    {
        $this->policyEngine = $policyEngine;
        $this->appRegistryService = $appRegistryService;
    }

    /**
     * Build dynamic menu hierarchy.
     */
    public function buildNavigation(User $user, $workspaceId)
    {
        // 1. Validate workspace access
        if (!$this->policyEngine->canAccessWorkspace($user, $workspaceId)) {
            return ['bottom_nav' => [], 'sidebar' => []];
        }

        $registryApps = $this->appRegistryService->getMobileApps();

        $bottomNav = [];
        $sidebar = [];

        // Always show home
        $bottomNav[] = ['id' => 'home', 'label' => 'Home', 'icon' => 'home'];

        foreach ($registryApps as $app) {
            // Very simple dynamic check: For example, check if they have permission to access the 'app.slug'
            // For now, if the app is active and mobile enabled, we add it to the sidebar.
            // If we had more granular registry logic (e.g. required_permission), we'd check it here.
            
            // Assume registry app maps to specific permissions or roles later
            $sidebar[] = [
                'id' => $app->app_slug,
                'label' => $app->app_name,
                'icon' => $app->app_icon ?? 'module',
                'type' => $app->app_type,
            ];
        }

        // Add conditional modules based on existing permissions
        if ($this->policyEngine->canAccessFeature($user, $workspaceId, 'estimates.view')) {
            $bottomNav[] = ['id' => 'estimates', 'label' => 'Estimates', 'icon' => 'document'];
        }

        if ($this->policyEngine->canAccessFeature($user, $workspaceId, 'users.view')) {
            $sidebar[] = ['id' => 'users', 'label' => 'User Management', 'icon' => 'users'];
        }

        return [
            'bottom_nav' => $bottomNav,
            'sidebar' => $sidebar,
        ];
    }
}
