<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MobileNavigationService;
use App\Services\DashboardService;
use App\Services\PolicyEngine;
use App\Services\AppRegistryService;

class MobileUiConfigController extends Controller
{
    private $navigationService;
    private $dashboardService;
    private $policyEngine;
    private $appRegistryService;

    public function __construct(
        MobileNavigationService $navigationService,
        DashboardService $dashboardService,
        PolicyEngine $policyEngine,
        AppRegistryService $appRegistryService
    ) {
        $this->navigationService = $navigationService;
        $this->dashboardService = $dashboardService;
        $this->policyEngine = $policyEngine;
        $this->appRegistryService = $appRegistryService;
    }

    public function registry(Request $request)
    {
        return response()->json([
            'apps' => $this->appRegistryService->getMobileApps()
        ]);
    }

    public function navigation(Request $request)
    {
        $request->validate(['workspace_id' => 'required|integer']);
        
        $navigation = $this->navigationService->buildNavigation($request->user(), $request->workspace_id);
        
        return response()->json($navigation);
    }

    public function dashboard(Request $request)
    {
        $request->validate(['workspace_id' => 'required|integer']);
        
        $widgets = $this->dashboardService->getWidgets($request->user(), $request->workspace_id);
        
        return response()->json(['widgets' => $widgets]);
    }

    public function sync(Request $request)
    {
        $request->validate(['workspace_id' => 'required|integer']);

        $user = $request->user();
        $workspaceId = $request->workspace_id;

        if (!$user->is_active) {
            return response()->json(['status' => 'inactive', 'action' => 'force_logout'], 403);
        }

        // Access to the current workspace revoked
        if (!$this->policyEngine->canAccessWorkspace($user, $workspaceId)) {
            return response()->json(['status' => 'active', 'action' => 'workspace_revoked']);
        }

        $navigation = $this->navigationService->buildNavigation($user, $workspaceId);
        $widgets = $this->dashboardService->getWidgets($user, $workspaceId);

        return response()->json([
            'status' => 'active',
            'action' => 'update_context',
            'context' => [
                'workspace_id' => $workspaceId,
                'navigation' => $navigation,
                'dashboard' => $widgets,
                // Lets mobile detect whether permissions changed
                'sync_hash' => md5(json_encode([$navigation, $widgets])),
            ],
        ]);
    }

    public function settings(Request $request)
    {
        $user = $request->user();

        // Dynamically build settings based on roles/features
        $settings = [
            'theme' => 'system',
            'language' => 'en',
            'push_notifications' => true,
            'features' => []
        ];

        if ($user->isAdmin()) {
            $settings['features'][] = 'device_management';
            $settings['features'][] = 'audit_logs';
        }

        return response()->json(['settings' => $settings]);
    }
}
