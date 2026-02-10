# Product Requirement Document (PRD): Nexus Identity Platform

## 1. Introduction
**Nexus Identity** is a centralized authentication and authorization service designed to serve as the Identity Provider (IdP) for a suite of SaaS applications. It provides a single source of truth for user identities, manages comprehensive Role-Based Access Control (RBAC), handles Single Sign-On (SSO) flows, and maintains strict audit trails for security compliance.

## 2. Objectives
- **Centralize Identity**: Eliminate siloed user tables across multiple apps.
- **Unified Access Control**: Manage permissions and roles globally or per application from a single dashboard.
- **Secure SSO**: Enable seamless, secure transitions between satellite applications using signed JWTs.
- **Compliance & Auditing**: Track every critical security action (login, role change, app creation) for accountability.

## 3. Target Audience
- **System Administrators**: Manage the Auth infrastructure, create apps, and define high-level roles.
- **Organization Admins**: Manage user access within their specific organizational context.
- **End Users**: Access multiple applications with a single set of credentials.

## 4. Technical Architecture
- **Framework**: Laravel 12
- **Frontend Stack**: Livewire 4 + Tailwind CSS (Alpine.js interaction)
- **Database**: Relational (MySQL/PostgreSQL compatible)
- **Authentication Protocol**: JWT-based SSO with signed payloads.

## 5. Core Features

### 5.1 Authentication System
- **Login/Logout**: Standard email/password authentication.
- **Single Sign-On (SSO)**:
  - Generates signed JWTs containing user identity and role claims.
  - Validates callback URLs against registered Application domains.
  - Seamless redirection to satellite apps.
  - **Security**: Prevents token replay and enforces access revocation.

### 5.2 Application Management
- **Registry**: CRUD interface for managing satellite applications (`/admin/apps`).
- **Attributes**:
  - `Name`: Display name.
  - `Slug`: Unique identifier.
  - `Domain/Callback URL`: Whitelisted return URL for SSO.
  - `Status`: Active, Inactive, Maintenance.

### 5.3 Role-Based Access Control (RBAC)
- **Granular Permissions**:
  - Defined in code/seeds (e.g., `users.view`, `estimates.create`).
  - Grouped logically (System, User Management, Finance, etc.).
- **Role Scoping**:
  - **Global Roles**: Apply system-wide (e.g., `Super Admin`).
  - **App-Scoped Roles**: Valid only within the context of a specific app (e.g., `Editor` for the "Blog App").
- **Dynamic Assignment**: Users can hold different roles for different apps.
- **Management UI**: Full CRUD for roles and permission assignment (`/admin/roles`).

### 5.4 User Management
- **User Directory**: List all users (`/admin/users`).
- **App Access Control**:
  - Toggle user access to specific applications.
  - Assign specific roles for that application access.
- **Validation**: Ensures users cannot be assigned roles that belong to a different application context.

### 5.5 Audit Logging
- **Scope**: Logs all "write" operations in critical modules (Auth, RBAC, App Management).
- **Data Points**:
  - Actor (Who performed the action).
  - Action (Created, Updated, Deleted).
  - Target (The record being affected).
  - Changes (JSON diff of Before/After values).
  - Metadata (IP Address, User Agent).
- **UI**: Read-only viewer for admins (`/admin/audit-logs`).

## 6. Database Schema Overview

### Core Tables
- `users`: Standard identity records.
- `apps`: Registry of satellite services.
- `roles`: Role definitions with `is_global` and `app_id` flags.
- `permissions`: Atomic capabilities.
- `audit_logs`: Immutable history of actions.

### Pivot Tables (Relationships)
- `user_app_accesses`: Links Users <-> Apps (includes `role_id` for context).
- `role_permissions`: Links Roles <-> Permissions.
- `user_roles`: (Legacy/Direct) Links Users <-> Roles directly if needed outside of app context.

## 7. UX/UI Design Principles
- **Modern & Clean**: Uses a minimalist, whitespace-heavy design inspired by Vercel/Linear.
- **Sidebar Navigation**: Persistent, collapsible sidebar for efficient admin navigation.
- **Responsive**: Fully functional on mobile and desktop.
- **Feedback**: Immediate visual feedback for actions (toasts, modal transitions).

## 8. Workflow Examples

### A. Admin Creates a New App
1. Admin navigates to **Applications**.
2. Clicks "Add Application".
3. Enters Name ("CRM"), Slug ("crm"), Domain ("https://crm.example.com").
4. System logs `app.created`.

### B. User Logs In via SSO
1. User visits `crm.example.com`.
2. Redirected to `auth.core/login`.
3. User enters credentials.
4. Nexus Identity validates access to "CRM" app.
5. Nexus Identity generates JWT with `role` claim for "CRM".
6. User redirected back to `crm.example.com?token=JWT`.

### C. Granting Access
1. Admin goes to **Users** -> **Manage Access**.
2. Finds "John Doe".
3. Toggles "CRM" access to **ON**.
4. Selects role "Manager" from CRM-scoped roles.
5. System saves and logs the access grant.

## 9. Future Roadmap
- **2FA/MFA**: Time-based One-Time Passwords (TOTP).
- **Social Login**: Google/Microsoft integration.
- **API Tokens**: Personal Access Tokens (PATs) for developer access.
- **Team/Organization Support**: Grouping users into tenants.
