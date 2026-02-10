# 🌐 Nexus Identity: Complete System Architecture & Intelligence

This document provides a comprehensive technical breakdown of the **Nexus Identity** ecosystem. It is designed for both human engineers and AI agents to understand, maintain, and extend the platform.

---

## 1. System Vision
**Nexus Identity** is a centralized **Identity and Access Management (IAM)** cluster. It functions as a "Universal Brain" for user identities, allowing multiple satellite applications (Saas products, internal tools) to share a single source of truth for users, roles, and permissions.

---

## 2. Core Architecture

### Identity Hub (This Instance)
*   **Engine**: Laravel 12 + Livewire 4.
*   **Identity Store**: Eloquent-based `User` models with enhanced security (2FA, recovery codes).
*   **Access Protocol**: RBAC (Role-Based Access Control) with two scopes:
    *   **Global**: Permissions that apply across the entire hub (e.g., `super_admin`).
    *   **Satellite-Scoped**: Permissions valid only within a specific application context.
*   **Security Vault**: Tracks every Issued JWT and prevents Replay Attacks via JTI (JWT ID) blacklisting.
*   **Security Ledger**: A high-fidelity audit trail mapping every `Actor` to an `Action` on a `Target`.

### Satellite Apps (The Consumers)
*   **Verification**: Uses asymmetric **RS256** signatures.
*   **Knowledge**: Only needs the **Public Key** from the Hub to verify users.
*   **Automation**: Automatically provisions/syncs users upon successful SSO handshake.

---

## 3. Data Schema Intelligence

### The Identity Node (`users`)
*   `two_factor_secret`: Encrypted MFA seed.
*   `isAdmin()`: Determines global cluster control.
*   `hasPermission(key, app_id)`: The primary authorization check.

### The Satellite Registry (`apps`)
*   `slug`: Used in JWT claims to identify the destination.
*   `domain`: Used for `aud` (audience) validation to prevent token hijacking.

### The Access Protocol (`roles` & `permissions`)
*   `is_global`: If true, role applies system-wide.
*   `app_id`: If set, role is specialized for that satellite.

---

## 4. The Nexus Protocol (SSO Workflow)

1.  **Request**: User visits Satellite A. Satellite A detects no session and redirects to `NexusHub/login`.
2.  **Challenge**: User authenticates at Nexus Hub (Password + 2FA).
3.  **Authorization**: Nexus Hub checks if the User has an `active` status for Satellite A.
4.  **Issue**: Nexus Hub signs a JWT with:
    *   `sub`: User ID
    *   `email`: Primary Identity
    *   `role`: The specific role assigned to the user for Satellite A.
    *   `aud`: The Satellite's domain.
    *   `jti`: A one-time-use cryptographic nonce.
5.  **Return**: User is redirected to `SatelliteA/callback?token=...`.
6.  **Trust**: Satellite A verifies the signature with the Hub's Public Key and accepts the identity.

---

## 5. Security Posture

### Asymmetric Encryption
We use **RSA-256**.
*   **Private Key**: Stored exclusively on the Nexus Hub. Used to sign tokens. **CRITICAL: NEVER EXPOSE.**
*   **Public Key**: Distributed to all Satellites. Used to verify tokens.

### Replay Defense
The **SSO Vault** (`used_sso_tokens`) stores every used JTI. If an attacker intercepts a token and tries to use it again, the Hub (or Satellite if implementing strict vaulting) will reject it.

---

## 6. Administrative Tools

*   **User Directory**: Full lifecycle management of identities.
*   **Satellite Registry**: Onboarding new satellites into the ecosystem.
*   **Capability Matrix**: Granular control over what roles can perform which actions.
*   **Nexus Ledger**: Searchable, filtered logs of all system mutations for compliance.

---

## 7. Operational Prompts for Agents

To have an AI agent build a new feature or satellite integration, use these:

**For a New Satellite Integration:**
> "I want to connect this app to a **Nexus Identity Hub**. I have the Public Key. Please create an SSO Callback handler that uses `firebase/php-jwt` to verify RS256 signatures, check the `aud` claim against our URL, and the `jti` for freshness. Map the incoming `role` claim to our local authorization system."

**For Adding a New Security Feature to the Hub:**
> "I need to add [Feature] to the Nexus Identity Cluster. Ensure the action is logged in the `Security Ledger` using the `AuditService`, and restricted to users with the `super_admin` role via the `isAdmin()` helper."
