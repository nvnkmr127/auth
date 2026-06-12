<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\PolicyEngine;

class MobilePolicyMiddleware
{
    private $policyEngine;

    public function __construct(PolicyEngine $policyEngine)
    {
        $this->policyEngine = $policyEngine;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Real-time access revocation check (Phase 36, 40)
        // If the Super Admin disabled the user, block immediately.
        if (!$user->is_active) {
            return response()->json(['status' => 'inactive', 'action' => 'force_logout', 'message' => 'Account disabled'], 403);
        }

        // If the request requires a specific workspace context, validate it in real-time
        $workspaceId = $request->header('X-Workspace-Id') ?? $request->input('workspace_id');
        
        if ($workspaceId && !$this->policyEngine->canAccessWorkspace($user, $workspaceId)) {
            return response()->json([
                'status' => 'active', 
                'action' => 'workspace_revoked', 
                'message' => 'Workspace access revoked'
            ], 403);
        }

        return $next($request);
    }
}
