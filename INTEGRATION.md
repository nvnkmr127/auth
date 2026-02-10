# 🔐 Nexus Identity: Full System Architecture & Integration Blueprint

This document serves as the **Single Source of Truth** for the Nexus Identity ecosystem. It explains the system's inner workings and provides a foolproof guide for integrating satellite applications.

---

## 🏗️ 1. System Philosophy & Architecture

**Nexus Identity** is a centralized **Identity Provider (IdP)**. It uses the **OIDC-Lite (OpenID Connect)** pattern with **Asymmetric RS256 Signatures**.

### Key Components:
1.  **Identity Provider (IdP) - This App**:
    *   Manages users, global roles, and app-specific roles.
    *   Generates **Signed JWTs** using a private RSA key.
    *   Provides a dashboard for Satellite Registry and Audit Logging.
2.  **Service Provider (SP) - Your Satellite App**:
    *   Trusts the IdP via a **Public RSA Key**.
    *   Validates incoming JWTs (checks signature, expiration, and audience).
    *   Automatically creates/logs in users based on verified claims.
3.  **Security Mechanisms**:
    *   **RS256**: Only the IdP can sign tokens; any app can verify them.
    *   **JTI (JSON Token Identifier)**: Every token has a unique ID to prevent replay attacks.
    *   **Short Expiry**: Tokens are valid only for 60 seconds to minimize the window of risk.

---

## 🛠️ 2. Phase 1: Configuring the IdP (This App)

To connect a new satellite, you must first register it in this system.

### Step-by-Step:
1.  **Register Application**:
    *   Navigate to **Satellite Registry** (`/admin/apps`).
    *   Click **Add Application**.
    *   **Slug**: Choose a unique identifier (e.g., `inventory-app`).
    *   **Callback URL**: The exact route where the satellite will receive tokens (e.g., `http://localhost:8001/sso/callback`).
2.  **Define Roles**:
    *   Navigate to **Access Protocol** (`/admin/roles`).
    *   Create roles specifically scoped to your new app.
3.  **Authorize Users**:
    *   Go to **User Directory** (`/admin/users`).
    *   Select a user and click **Manage Access**.
    *   Enable access for the new app and assign a role.
4.  **Extract the Key**:
    *   Run this command in the project root:
    ```bash
    cat storage/jwt/public.pem
    ```
    *   Copy the output exactly.

---

## 🤖 3. Phase 2: Building the Satellite (Vibe Coding Guide)

If you are using an AI agent (like Vibe) to build the satellite app, give it these specific instructions.

### **The "Master Prompt" for your Satellite Agent:**
> "I want to integrate this Laravel app as a Satellite/Service Provider for an Nexus Identity IdP. Here is the technical manifest:
> 
> 1. **Protocol**: RS256 JWT-based SSO.
> 2. **Dependencies**: Install `firebase/php-jwt`.
> 3. **Environment**:
>    - `AUTH_CORE_URL`: The Identity Provider URL.
>    - `AUTH_CORE_PUBLIC_KEY`: The RS256 Public Key (provided in BEGIN/END format).
>    - `APP_URL`: Must match the 'aud' claim in the JWT.
> 
> 4. **Routes**: Create a Route `GET /sso/callback` handled by `SsoController@callback`.
> 
> 5. **Controller Logic**:
>    - Extract `token` from URL.
>    - Verify signature using `AUTH_CORE_PUBLIC_KEY` and `RS256`.
>    - Validate `exp` (not expired) and `aud` (matches this app's URL).
>    - Use the `email` claim to `firstOrCreate` a User in this app.
>    - Authenticate using `Auth::login($user)`.
> 
> 6. **Middleware**: Update `Authenticate.php` to redirect unauthenticated web requests to `AUTH_CORE_URL/login`."

---

## 💻 4. Phase 3: Technical Implementation Details

### **A. Environment Configuration (.env)**
```env
AUTH_CORE_URL=http://localhost:8000
# Paste public key with \n for newlines
AUTH_CORE_PUBLIC_KEY="-----BEGIN PUBLIC KEY-----\n...\n-----END PUBLIC KEY-----"
```

### **B. Controller Blueprint**
```php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

public function callback(Request $request) {
    $token = $request->query('token');
    $key = str_replace('\n', "\n", env('AUTH_CORE_PUBLIC_KEY'));
    
    // 1. Decode & Verify
    $decoded = JWT::decode($token, new Key($key, 'RS256'));

    // 2. Validate Audience
    if ($decoded->aud !== config('app.url')) {
        return response('Unauthorized Audience', 403);
    }

    // 3. Authenticate
    $user = User::firstOrCreate(
        ['email' => $decoded->email],
        ['name' => $decoded->name ?? 'SSO User', 'password' => bcrypt(Str::random(24))]
    );
    
    Auth::login($user);
    return redirect()->intended('/dashboard');
}
```

---

## 🧪 5. Testing the Handshake

1.  **Initiate**: Visit `/dashboard` on the satellite app.
2.  **Redirect**: You should be bounced to Nexus Identity Login.
3.  **Auth**: Log in with your Nexus Identity credentials.
4.  **Handoff**: Nexus Identity signs a JWT and redirects you back.
5.  **Success**: You land on the satellite dashboard, authenticated as the same user.

---

## 🚨 Security Checklist
- [ ] **Production Key Rotation**: Never commit `private.pem` to Git.
- [ ] **NTP Sync**: Ensure server clocks are synchronized (JWT `exp` claim will fail if clocks drift > 60s).
- [ ] **HTTPS Only**: Always use TLS in production; JWTs are sensitive.
- [ ] **Scope Validation**: Optionally check `$decoded->role` to assign local permissions.
