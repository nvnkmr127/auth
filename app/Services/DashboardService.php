<?php

namespace App\Services;

use App\Models\User;

class DashboardService
{
    private $policyEngine;

    public function __construct(PolicyEngine $policyEngine)
    {
        $this->policyEngine = $policyEngine;
    }

    /**
     * Generate dynamic dashboard widgets.
     */
    public function getWidgets(User $user, $workspaceId)
    {
        $widgets = [];

        // All users get a welcome widget
        $widgets[] = ['type' => 'welcome_banner'];

        if ($this->policyEngine->canAccessFeature($user, $workspaceId, 'estimates.view')) {
            $widgets[] = ['type' => 'recent_estimates'];
        }

        if ($this->policyEngine->canAccessFeature($user, $workspaceId, 'users.view')) {
            $widgets[] = ['type' => 'user_statistics'];
        }

        return $widgets;
    }
}
