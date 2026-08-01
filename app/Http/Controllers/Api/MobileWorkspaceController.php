<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\App;
use Illuminate\Support\Facades\Auth;

class MobileWorkspaceController extends Controller
{
    /**
     * Get all workspaces assigned to the authenticated user.
     * In this architecture, an 'App' record in the web system acts as a 'Workspace'.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->is_active) {
            return response()->json(['status' => 'inactive'], 403);
        }

        // Fetch apps (workspaces) the user has access to
        if ($user->isAdmin()) {
            $apps = App::where('status', 'active')->get();
        } else {
            $appIds = $user->appAccesses()->pluck('app_id');
            $apps = App::whereIn('id', $appIds)->where('status', 'active')->get();
        }

        $workspaces = $apps->map(function ($app, $index) {
            return [
                'id' => $app->id,
                'name' => $app->name . ' Workspace',
                'default' => $index === 0, // Mocking first as default for now
            ];
        });

        return response()->json([
            'workspaces' => $workspaces
        ]);
    }

    /**
     * Get specific workspace details, allowed applications, and permissions.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        if (!$user || !$user->is_active) {
            return response()->json(['status' => 'inactive'], 403);
        }

        // Validate access to this workspace (app)
        if (!$user->isAdmin() && !$user->appAccesses()->where('app_id', $id)->exists()) {
            return response()->json(['message' => 'Unauthorized workspace access'], 403);
        }

        $app = App::where('status', 'active')->findOrFail($id);

        return response()->json([
            'workspace' => [
                'id' => $app->id,
                'name' => $app->name . ' Workspace',
            ],
            'applications' => $this->applicationsFor($user, $app),
            'permissions' => $this->permissionsFor($user, $app),
        ]);
    }

    /**
     * Allowed mobile applications for a user within an app (workspace) context.
     */
    private function applicationsFor($user, App $app): array
    {
        $applications = [['name' => 'CRM', 'slug' => 'crm']];

        if ($user->hasPermission('estimates.view', $app->id)) {
            $applications[] = ['name' => 'Estimator', 'slug' => 'estimator'];
        }

        return $applications;
    }

    /**
     * Flattened permission keys for a user within an app (workspace) context.
     */
    private function permissionsFor($user, App $app): array
    {
        if ($user->isAdmin()) {
            return ['*'];
        }

        $roles = $user->roles()->where('app_id', $app->id)->orWhere('is_global', true)->with('permissions')->get();

        $permissions = [];
        foreach ($roles as $role) {
            foreach ($role->permissions as $permission) {
                $permissions[] = $permission->key;
            }
        }

        return array_values(array_unique($permissions));
    }

    /**
     * Switch workspace and return updated permissions/menus without re-login.
     */
    public function switch(Request $request)
    {
        $request->validate([
            'workspace_id' => 'required|integer'
        ]);

        $user = $request->user();

        if (!$user || !$user->is_active) {
            return response()->json(['status' => 'inactive'], 403);
        }

        $workspaceId = $request->workspace_id;

        // Validate access
        if (!$user->isAdmin() && !$user->appAccesses()->where('app_id', $workspaceId)->exists()) {
            return response()->json(['message' => 'Unauthorized workspace access'], 403);
        }

        $app = App::where('status', 'active')->findOrFail($workspaceId);

        // For stateless JWTs we just return the new context data (permissions, apps).
        return response()->json([
            'message' => 'Workspace switched successfully',
            'workspace' => [
                'id' => $app->id,
                'name' => $app->name . ' Workspace',
            ],
            'applications' => $this->applicationsFor($user, $app),
            'permissions' => $this->permissionsFor($user, $app),
        ]);
    }
}
