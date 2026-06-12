<?php

namespace App\Services;

use App\Models\ApplicationRegistry;

class AppRegistryService
{
    /**
     * Get all mobile-enabled applications from the registry.
     */
    public function getMobileApps()
    {
        return ApplicationRegistry::where('status', 'active')
            ->where('mobile_enabled', true)
            ->orderBy('sort_order')
            ->get();
    }
}
