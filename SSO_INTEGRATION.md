# SSO Master Integration & Troubleshooting Guide

This document contains the "Gold Standards" for ensuring your Single Sign-On (SSO) between the **Auth Portal** and **Satellite Apps** works perfectly every time.

---

## 🚀 The "Perfect Setup" Checklist

### 1. URL Precision (The #1 cause of failures)
- **Auth Portal `.env`**: `APP_URL` must be exactly `https://login.onestudio.co.in` (No trailing slash).
- **Satellite App `.env`**: `AUTH_CORE_URL` must match the Portal URL exactly (including `https`).
- **Satellite App `.env`**: `SSO_JWT_AUDIENCE` should be the base origin only: `https://estimator.onestudio.co.in`.
- **Portal Database**: The `domain` field for the app MUST be just the base URL: `https://estimator.onestudio.co.in`. **NEVER** put `/sso/callback` in the database.

### 2. Security Key Synchronization
- The **Private Key** lives ONLY on the Auth Portal (`storage/jwt/private.pem`).
- The **Public Key** content must be copied to every Satellite App's `.env` under `AUTH_CORE_PUBLIC_KEY`.
- **Note:** If you generate new keys on the portal, you MUST update the `.env` on every single satellite app or they will all stop working (403 error).

### 3. Role Mapping (The #1 cause of missing features)
- The Auth Portal sends role keys like `super_admin`, `estimator_admin`, etc.
- **Satellite App Rule:** If the role isn't in your `config/sso.php` mapping, the user will be logged in as a regular guest/basic user.
- **Maintenance:** Every time you add a new role type in the Auth Portal, add it to the satellite app's `role_mapping` array.

---

## 🛠 Maintenance Commands
Whenever you change a setting in `.env` or a config file, you **MUST** run these commands on that server:

```bash
# Clear all cached configurations
php artisan config:clear

# Clear route cache (if you changed sso/callback path)
php artisan route:clear

# Optional: Restart the queue if you use background jobs
php artisan queue:restart
```

---

## 🔍 Troubleshooting Flowchart

| Symptom | Probable Cause | Fix |
| :--- | :--- | :--- |
| **"Session Locked" on Portal** | No `user_app_access` record. | Ensure user is an Admin OR has explicit app access. |
| **"Security Exception" (Portal)** | Missing Private Key. | SSH into Portal and run OpenSSL key generation commands. |
| **"404 Not Found" (on Callback)** | Doubled URLs like `/sso/callback/sso/callback`. | Remove `/sso/callback` from the App's `domain` in the Portal database. |
| **"403 Invalid Audience"** | Mismatch in protocol or trailing slash. | Check `SSO_JWT_AUDIENCE` and the Portal's `domain` field. They must be identical. |
| **"403 OpenSSL validate key"** | Mismatched Public/Private keys. | Re-copy `public.pem` from Portal to Satellite App `.env`. |
| **Logged in, but no features/admin menu** | Role Mapping mismatch. | Check if the Portal role (e.g., `super_admin`) is in the App's `role_mapping` config. |

---

## 🔐 Advanced Tips
- **Silent Login:** We implemented a redirect in `AuthenticatedSessionController` so users never see the local login page; they are sent straight to the Portal.
- **Backdoor Access:** If SSO is ever down, you can access the local login by adding `?local=1` to the URL.
- **Centralized Logout:** Always use the `{PORTAL_URL}/logout?redirect={LOCAL_URL}` pattern so users are logged out of the entire ecosystem at once.
