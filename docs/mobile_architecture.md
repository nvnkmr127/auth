# Mobile Application Architecture

## 1. Overview
The Mobile App acts as a thin, dynamic container driven entirely by the Auth System. All navigation, dashboard widgets, applications, and permissions are determined by the backend `PolicyEngine` and `WorkspaceContextService`. This allows the Super Admin to control mobile access in real-time without requiring mobile app updates.

## 2. Dynamic App Registry (Phase 31)
The `application_registry` table stores metadata about available applications (CRM, Estimator, HRMS, etc.). 
- Endpoint: `GET /api/mobile/v1/app-registry`
- Mobile Action: Fetch available apps on launch and store them in the local dictionary to resolve app icons, names, and slugs dynamically.

## 3. Dynamic UI Generation (Phase 32 & 33)
The backend determines what the user can see based on their roles in the current Workspace.
- Endpoints: 
  - `GET /api/mobile/v1/navigation`
  - `GET /api/mobile/v1/dashboard/widgets`
- Mobile Action: Build the sidebar, bottom navigation, and dashboard screen strictly from this JSON payload. Hardcoded menus are explicitly prohibited.

## 4. Policy Engine & Access Revocation (Phase 36)
Every API request passes through the `MobilePolicyMiddleware`.
- If the Super Admin revokes a user's access, the next API request will return HTTP 403:
  ```json
  { "status": "inactive", "action": "force_logout" }
  ```
- Mobile Action: Intercept all 403s with `status: inactive`. Clear local storage, kill all active screens, and navigate to the Login view immediately.

## 5. EventBus Integration (Phase 38)
The Mobile App must implement a centralized `EventBus` to ensure state consistency across all tabs.
- Events to Emit: `UserLoggedIn`, `UserLoggedOut`, `WorkspaceChanged`, `PermissionUpdated`.
- Listeners: Navigation controllers, Dashboard widgets, Settings.

## 6. Real-Time Synchronization (Phase 37)
When the app resumes from the background or receives a silent push notification indicating an access change, it must hit:
- Endpoint: `POST /api/mobile/v1/sync`
- Mobile Action: Compare the returned `sync_hash` with the local hash. If they differ, dispatch `WorkspaceChanged` or `PermissionUpdated` to refresh the UI immediately.

## 7. Future App Plugin Architecture (Phase 35)
When a new app (e.g., "HRMS") is added to the Auth System, it will automatically appear in `/api/mobile/v1/navigation`. 
- Mobile Action: Map the generic `app_slug` string returned by the API to a generic web-view or native wrapper component if no specific native implementation exists yet.
