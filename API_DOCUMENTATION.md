# 🔐 Nexus Identity API Documentation

Complete guide to using the Nexus Identity authentication system, including endpoints, workflows, and integration examples.

---

## Table of Contents
1. [Getting Started](#getting-started)
2. [Authentication & SSO Flow](#authentication--sso-flow)
3. [User Endpoints](#user-endpoints)
4. [Admin Endpoints](#admin-endpoints)
5. [Integration Guide](#integration-guide)
6. [Example Workflows](#example-workflows)

---

## Getting Started

### Base URL
```
http://localhost:8000  # Development
https://auth.example.com  # Production
```

### API Architecture
Nexus Identity uses a **hybrid approach**:
- **Web Routes** (UI-based): Livewire components for browser interactions
- **API Integration**: JSON Web Tokens (JWT) for satellite app authentication
- **Protocol**: RS256 asymmetric JWT signing

---

## Authentication & SSO Flow

### 1. User Login

**Endpoint**: `GET /login`

**Description**: Initiates the login process. Users are presented with email/password form.

**Parameters**: None

**Response**: HTML form with:
- Email input field
- Password input field
- Redirect parameter (optional)

**Example:**
```bash
curl -X GET http://localhost:8000/login
```

---

### 2. Logout

**Endpoint**: `GET/POST /logout`

**Description**: Terminates user session and optionally redirects to an allowed domain.

**Parameters**:
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `redirect` | string | No | Allowed redirect domain after logout |

**Response**: Redirects to home page or specified allowed domain

**Example:**
```bash
# Simple logout
curl -X GET http://localhost:8000/logout

# Logout with redirect
curl -X GET "http://localhost:8000/logout?redirect=https://example.com/dashboard"
```

**Security Notes**:
- Only whitelisted domains in `app.allowed_redirect_domains` config are allowed
- Relative paths (starting with `/`) are always allowed
- Session is invalidated and token regenerated

---

### 3. SSO Callback Handler

**Endpoint**: `GET /sso/callback`

**Description**: Handles SSO token after user authenticates at Nexus Identity hub.

**Parameters**:
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `token` | string | Yes | Signed JWT from Nexus Identity |

**Response**: Redirects to satellite app dashboard with authenticated session

**JWT Claims Included**:
```json
{
  "sub": "USER_ID",
  "email": "user@example.com",
  "name": "User Name",
  "role": "admin",
  "aud": "satellite.example.com",
  "jti": "unique_token_identifier",
  "exp": 1234567890,
  "iat": 1234567830
}
```

**Example:**
```bash
curl -X GET "http://localhost:8000/sso/callback?token=eyJhbGciOiJSUzI1NiIs..."
```

**Verification Details**:
- Signature verified using RS256 (RSA with SHA-256)
- Token expiration checked (default 60 seconds)
- Audience claim validated against app domain
- JTI (JWT ID) checked against used tokens to prevent replay attacks

---

## User Endpoints

### 4. Dashboard

**Endpoint**: `GET /dashboard`

**Authentication**: Required (middleware: `auth`)

**Description**: User dashboard showing available applications and personal information.

**Response**: HTML dashboard with:
- Authenticated user profile
- Available applications
- Quick access links

**Example:**
```bash
curl -X GET http://localhost:8000/dashboard \
  -H "Cookie: LARAVEL_SESSION=xxx"
```

---

### 5. Applications List

**Endpoint**: `GET /apps`

**Authentication**: Required (middleware: `auth`)

**Component**: `AppSelector` Livewire component

**Description**: Lists all applications the authenticated user has access to.

**Response**: List of applications with:
- Application name
- Application description
- Access status
- Action links (Open app)

**Data Returned**:
```json
{
  "apps": [
    {
      "id": 1,
      "name": "CRM System",
      "slug": "crm",
      "domain": "https://crm.example.com"
    },
    {
      "id": 2,
      "name": "Blog Platform",
      "slug": "blog",
      "domain": "https://blog.example.com"
    }
  ]
}
```

**Example:**
```bash
curl -X GET http://localhost:8000/apps \
  -H "Cookie: LARAVEL_SESSION=xxx"
```

---

### 6. Open Application (SSO)

**Endpoint**: `GET /apps/{app}/open`

**Authentication**: Required (middleware: `auth`)

**Parameters**:
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `app` | integer | Yes | Application ID |

**Description**: Generates an SSO token and redirects user to the satellite application.

**Authorization**:
- User must be admin OR
- User must have active access to the application

**Response**: Redirect to satellite app with SSO token
```
https://crm.example.com/sso/callback?token=eyJhbGciOiJSUzI1NiIs...
```

**Error Responses**:
- **403 Forbidden**: User doesn't have access to application
- **404 Not Found**: Application doesn't exist

**Example:**
```bash
curl -X GET http://localhost:8000/apps/1/open \
  -H "Cookie: LARAVEL_SESSION=xxx"
```

---

## Admin Endpoints

### 7. User Management

**Endpoint**: `GET /admin/users`

**Authentication**: Required + Admin only (middleware: `auth`, `AdminMiddleware`)

**Component**: `UserList` Livewire component

**Description**: Lists all users in the system with management capabilities.

**Features**:
- Search/filter users
- View user details
- Manage user app access
- Edit user information
- Deactivate/activate users

**Data Displayed**:
```json
{
  "users": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "roles": ["Super Admin"],
      "created_at": "2026-01-15T10:30:00Z"
    }
  ]
}
```

---

### 8. User App Access Management

**Endpoint**: `GET /admin/users/{user}/apps`

**Authentication**: Required + Admin only

**Component**: `UserAppAccessManager` Livewire component

**Parameters**:
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `user` | integer | Yes | User ID |

**Description**: Manages which applications a specific user can access and their roles.

**Actions Available**:
- Grant/revoke application access
- Assign roles for specific applications
- Validate role/app compatibility

**Validation**:
- Cannot assign roles from one app to access another app
- Only app-scoped or global roles can be assigned

**Example Response**:
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  },
  "available_apps": [
    {
      "id": 1,
      "name": "CRM",
      "has_access": true,
      "assigned_role": "Manager"
    },
    {
      "id": 2,
      "name": "Blog",
      "has_access": false,
      "assigned_role": null
    }
  ]
}
```

---

### 9. Audit Logs

**Endpoint**: `GET /admin/audit-logs`

**Authentication**: Required + Admin only

**Component**: `AuditLogViewer` Livewire component

**Description**: View immutable audit trail of all system actions.

**Logged Actions**:
- User authentication
- User creation/deletion/updates
- Role assignments
- Permission changes
- Application registry changes
- Settings modifications

**Log Entry Structure**:
```json
{
  "id": 1,
  "actor_id": 2,
  "actor_name": "Admin User",
  "action": "created",
  "target_type": "User",
  "target_id": 5,
  "changes": {
    "before": {},
    "after": {
      "name": "New User",
      "email": "new@example.com"
    }
  },
  "metadata": {
    "ip_address": "192.168.1.1",
    "user_agent": "Mozilla/5.0..."
  },
  "created_at": "2026-01-15T10:30:00Z"
}
```

---

### 10. Role Management

**Endpoint**: `GET /admin/roles`

**Authentication**: Required + Admin only

**Component**: `RoleManager` Livewire component

**Description**: Create, edit, and manage roles (global or app-scoped).

**Available Actions**:
- Create new role
- Edit role details
- Assign permissions to roles
- Delete roles
- Toggle global vs app-scoped

**Role Structure**:
```json
{
  "id": 1,
  "name": "Editor",
  "description": "Can create and edit content",
  "is_global": false,
  "app_id": 1,
  "permissions": [
    "posts.view",
    "posts.create",
    "posts.edit",
    "posts.delete"
  ]
}
```

---

### 11. Permission Management

**Endpoint**: `GET /admin/permissions`

**Authentication**: Required + Admin only

**Component**: `PermissionManager` Livewire component

**Description**: View and manage available permissions in the system.

**Permission Structure**:
```json
{
  "id": 1,
  "key": "users.view",
  "description": "Can view user list",
  "category": "User Management"
}
```

---

### 12. Application Management

**Endpoint**: `GET /admin/apps`

**Authentication**: Required + Admin only

**Component**: `AppManager` Livewire component

**Description**: Register and manage satellite applications.

**Available Actions**:
- Add new application
- Edit app details
- Delete applications
- View app status
- Manage app credentials

**Application Entry Fields**:
```json
{
  "id": 1,
  "name": "CRM System",
  "slug": "crm",
  "domain": "https://crm.example.com",
  "callback_url": "https://crm.example.com/sso/callback",
  "status": "active",
  "created_at": "2026-01-15T10:30:00Z"
}
```

---

### 13. SSO Sessions

**Endpoint**: `GET /admin/sso-sessions`

**Authentication**: Required + Admin only

**Component**: `SsoSessions` Livewire component

**Description**: Monitor active SSO sessions and token usage.

**Information Displayed**:
- Active sessions per user
- Session IP and user agent
- Session creation time
- Token validity status

---

### 14. Settings Management

**Endpoint**: `GET /admin/settings`

**Authentication**: Required + Admin only

**Component**: `SettingsManager` Livewire component

**Description**: Manage system-wide settings.

**Configurable Settings**:
- Application name and branding
- Session timeout duration
- Token expiration times
- Allowed redirect domains
- 2FA requirements
- Audit logging retention

---

## Profile Endpoints

### 15. API Tokens

**Endpoint**: `GET /profile/api-tokens`

**Authentication**: Required (middleware: `auth`)

**Component**: `ApiTokens` Livewire component

**Description**: Manage personal API tokens for programmatic access.

**Available Actions**:
- Generate new token
- Revoke existing tokens
- View token usage
- Regenerate tokens

---

### 16. Security Settings

**Endpoint**: `GET /profile/security`

**Authentication**: Required (middleware: `auth`)

**Component**: `Security` Livewire component

**Description**: Manage account security settings.

**Features**:
- Change password
- Enable/disable two-factor authentication
- View recovery codes
- Review login history
- Manage active sessions

---

### 17. Devices

**Endpoint**: `GET /profile/devices`

**Authentication**: Required (middleware: `auth`)

**Component**: `Devices` Livewire component

**Description**: Manage trusted devices.

**Information Available**:
- Device name and type
- Last access time
- IP address
- Browser/OS information
- Remove device option

---

## Integration Guide

### For Satellite Application Developers

#### Step 1: Register Your App

Navigate to `/admin/apps` and create a new application entry:

```
Name: My App
Slug: my-app
Domain: https://myapp.example.com
Callback URL: https://myapp.example.com/sso/callback
```

#### Step 2: Obtain Public Key

Run in Nexus Identity terminal:
```bash
cat storage/jwt/public.pem
```

Copy the entire key content including BEGIN/END markers.

#### Step 3: Configure Your Satellite App

Set environment variables:
```env
AUTH_CORE_URL=https://auth.example.com
AUTH_CORE_PUBLIC_KEY="-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA...
-----END PUBLIC KEY-----"
```

#### Step 4: Implement SSO Callback

```php
<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class SsoController extends Controller
{
    public function callback(Request $request)
    {
        $token = $request->query('token');
        
        if (!$token) {
            return response('Token missing', 400);
        }

        try {
            // Decode and verify JWT
            $key = str_replace('\n', "\n", env('AUTH_CORE_PUBLIC_KEY'));
            $decoded = JWT::decode($token, new Key($key, 'RS256'));

            // Validate audience
            if ($decoded->aud !== config('app.url')) {
                return response('Invalid audience', 403);
            }

            // Find or create user
            $user = User::firstOrCreate(
                ['email' => $decoded->email],
                [
                    'name' => $decoded->name ?? 'SSO User',
                    'password' => bcrypt(Str::random(24))
                ]
            );

            // Authenticate user
            Auth::login($user);

            return redirect()->intended('/dashboard');

        } catch (\Exception $e) {
            return response('Authentication failed: ' . $e->getMessage(), 401);
        }
    }
}
```

#### Step 5: Update Authentication Middleware

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Authenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            return $next($request);
        }

        // Redirect to Nexus Identity login
        $redirectUrl = url()->current();
        return redirect(
            env('AUTH_CORE_URL') . '/login?redirect=' . urlencode($redirectUrl)
        );
    }
}
```

---

## Example Workflows

### Workflow 1: User Login & Application Access

```
1. User visits https://myapp.example.com/dashboard
2. Middleware detects no session
3. User redirected to https://auth.example.com/login
4. User enters email and password
5. System validates credentials
6. System checks user has access to "myapp"
7. System generates signed JWT with:
   - User ID
   - User email
   - Assigned role for "myapp"
   - Audience: "https://myapp.example.com"
8. User redirected to https://myapp.example.com/sso/callback?token=JWT
9. Satellite app verifies signature using public key
10. Validates audience matches
11. Creates/retrieves user from token claims
12. User authenticated and redirected to dashboard
```

### Workflow 2: Administrator Creates New User

```
1. Admin navigates to /admin/users
2. Clicks "Add User"
3. Fills form: Name, Email, Initial Password
4. Clicks "Create"
5. System creates user record
6. System logs action in audit_logs as "created"
7. Redirects admin to /admin/users/{user}/apps
8. Admin selects applications to grant access
9. Admin assigns appropriate roles per application
10. System validates role/app compatibility
11. System creates user_app_access records
12. System logs access grants in audit_logs
13. User receives notification email with credentials
```

### Workflow 3: Monitoring User SSO Activity

```
1. Admin navigates to /admin/sso-sessions
2. Views list of active SSO sessions
3. Checks session details:
   - User email
   - Logged-in application
   - Session IP address
   - Browser/Device info
   - Session creation time
4. Can revoke suspicious sessions if needed
5. Checks /admin/audit-logs for auth events
6. Filters by user email or action type
7. Reviews login attempts, role changes, etc.
```

### Workflow 4: Multi-App User Journey

```
1. User logs in once at Nexus Identity
2. User is redirected to first app (CRM)
3. CRM SSO handler authenticates user
4. User navigates to second app (Blog)
5. Blog detects no local session but sees valid Nexus token
6. Blog directly redirects to /apps/{blog}/open
7. Nexus generates new JWT with blog context
8. User lands on Blog without re-entering password
```

---

## Error Handling

### Common HTTP Status Codes

| Code | Scenario | Example |
|------|----------|---------|
| 200 | Successful request | Login form rendered |
| 401 | Unauthorized | Invalid credentials provided |
| 403 | Forbidden | User lacks admin permission |
| 404 | Not found | User/app doesn't exist |
| 422 | Validation failed | Required field missing |
| 500 | Server error | Database connection failed |

### JWT-Specific Errors

| Error | Cause | Resolution |
|-------|-------|-----------|
| InvalidSignatureException | Wrong public key or modified token | Verify public key is current |
| ExpiredException | Token older than 60 seconds | Request new token |
| InvalidArgumentException | Malformed token | Ensure full JWT in callback |
| InvalidAudienceException | Token for different app | Verify app domain in config |

---

## Rate Limiting

**Login Route**: Rate limited to prevent brute force attacks

- **Default**: 5 attempts per minute per IP
- **Config**: `config/app.php` in `RateLimiter::for('login')`

```php
RateLimiter::for('login', function (Request $request) {
    return Limit::perMinute(5)->by($request->ip());
});
```

---

## Security Best Practices

1. **Always use HTTPS** in production for JWT transmission
2. **Never expose private key** - keep `storage/jwt/private.pem` secure
3. **Validate all tokens** before trusting user identity
4. **Check audience claim** to prevent token misuse across apps
5. **Enable 2FA** for admin accounts
6. **Monitor audit logs** for suspicious activities
7. **Rotate keys** periodically in production
8. **Use short expiration times** (default 60 seconds) for tokens
9. **Implement JTI blacklisting** for replay attack prevention
10. **Sync server clocks** via NTP to avoid JWT expiration issues

---

## Troubleshooting

### "Invalid Signature" Error

**Cause**: Public key doesn't match the token's signature

**Solution**:
```bash
# Get current public key
cat storage/jwt/public.pem

# Verify it matches your environment variable
echo $AUTH_CORE_PUBLIC_KEY
```

### "Token Expired" Error

**Cause**: More than 60 seconds elapsed since token generation

**Solution**: Request a fresh token - tokens have intentionally short lifespans

### "Invalid Audience" Error

**Cause**: Token's `aud` claim doesn't match app domain

**Solution**:
```php
// In satellite app
if ($decoded->aud !== env('APP_URL')) {
    // Mismatch detected
    // Check that callback is using correct domain
}
```

### Redirect Loop Between Apps

**Cause**: Session not properly established after SSO

**Solution**: Ensure `Auth::login($user)` is called in callback handler and session is saved

---

## API Reference Summary

| Endpoint | Method | Auth | Purpose |
|----------|--------|------|---------|
| `/` | GET | No | Home page |
| `/login` | GET | No | Login form |
| `/logout` | GET/POST | Yes | Logout user |
| `/dashboard` | GET | Yes | User dashboard |
| `/apps` | GET | Yes | List user apps |
| `/apps/{id}/open` | GET | Yes | Open app with SSO |
| `/sso/callback` | GET | No | SSO token callback |
| `/profile/api-tokens` | GET | Yes | Manage API tokens |
| `/profile/security` | GET | Yes | Security settings |
| `/profile/devices` | GET | Yes | Manage devices |
| `/admin/users` | GET | Admin | User management |
| `/admin/users/{id}/apps` | GET | Admin | User app access |
| `/admin/audit-logs` | GET | Admin | View audit logs |
| `/admin/roles` | GET | Admin | Role management |
| `/admin/permissions` | GET | Admin | Permission mgmt |
| `/admin/apps` | GET | Admin | App registry |
| `/admin/sso-sessions` | GET | Admin | SSO sessions |
| `/admin/settings` | GET | Admin | System settings |

---

## Support & Questions

For issues or questions:
1. Check `/admin/audit-logs` for system activity
2. Review `storage/logs/` for application logs
3. Verify configuration in `config/` directory
4. Check `.env` file for required variables
5. Review INTEGRATION.md for satellite app setup

