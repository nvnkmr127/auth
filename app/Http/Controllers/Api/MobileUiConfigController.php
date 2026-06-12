<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MobileNavigationService;
use App\Services\DashboardService;
use App\Services\AccessSyncService;
use App\Services\AppRegistryService;

class MobileUiConfigController extends Controller
{
    private $navigationService;
    private $dashboardService;
    private $syncService;
    private $appRegistryService;

    public function __construct(
        MobileNavigationService $navigationService, 
        DashboardService $dashboardService,
        AccessSyncService $syncService,
        AppRegistryService $appRegistryService
    ) {
        $this->navigationService = $navigationService;
        $this->dashboardService = $dashboardService;
        $this->syncService = $syncService;
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
        
        $syncData = $this->syncService->sync($request->user(), $request->workspace_id);
        
        if ($syncData['status'] === 'inactive') {
            return response()->json($syncData, 403);
        }
        
        return response()->json($syncData);
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
