# Nexus Identity Platform: Features & Summary

## Project Summary
**Nexus Identity** is a centralized Identity and Access Management (IAM) cluster operating as a Unified Identity Provider (IdP). Built to serve a suite of interconnected SaaS applications, it acts as a "Universal Brain" eliminating siloed user tables across multiple related software products. It centralizes control, security, and access via a unified dashboard containing robust role-based access control, strict compliance audit trails, and secure JWT-based Single Sign-On (SSO) handshakes.

## Tech Stack
- **Backend framework**: Laravel 12 (PHP ^8.2)
- **Frontend Stack**: Livewire 4 + Tailwind CSS with Alpine.js interactions.
- **Database**: Relational Database (MySQL/PostgreSQL compatible)
- **Security Protocols**: Asymmetric RS256 signatures, single-use JTIs for replay defense, JSON Web Tokens (JWT) using `firebase/php-jwt`.
- **Additional Tools**: Two-Factor Authentication via `pragmarx/google2fa`.

## Core Features

### 1. Single Sign-On (SSO) Engine
- Centralized login hub maintaining a single session across all interconnected applications.
- Issues secure, cryptographically signed JWTs enriched with detailed user identity, specific role claims, and intended destination (audience).
- Verifies callback URLs rigorously against an internal registry before redirecting users.

### 2. Application Registry (Satellite Management)
- Administrative dashboard (`/admin/apps`) for onboarding new satellite services into the ecosystem.
- Configurable settings for each app including its unique identifier (slug), domain/callback rules, and active status.

### 3. Advanced Role-Based Access Control (RBAC)
- **Global Constraints**: Administrative and system-wide roles applicable to the identity hub itself.
- **Satellite Scopes**: Application-specific roles uniquely valid only when a user interacts with a connected service (e.g., an Editor only on the "Blog App").
- A comprehensive capability matrix defining atomic capabilities like `users.view` or `estimates.create`.

### 4. Granular User Provisioning
- Full lifecycle management interfaces for identities.
- App-level boundary control: Prevents misconfiguration by ensuring an assigned "Manager" role strictly binds to its designated application target and not another.

### 5. System Auditing (The Nexus Ledger)
- Strict compliance tracking maintaining an immutable ledger of every write-operation mutating the security state (e.g., login events, role modifications).
- Detailed attributes tracked per action encompassing the Actor, the Target, IP Address, precise Data Diff (Before/After JSON), and User Agent. 

### 6. Built-in Security Vault
- Leverages Public/Private Key infrastructure: Tokens are signed by an unexposed Private Key and verified utilizing distributed Public Keys at the edges.
- Stores used JWT IDs in a secure vault to detect and intercept token replay attacks.
