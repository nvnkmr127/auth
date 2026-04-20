# 📐 Nexus Identity: View Creation & Livewire Component Guide

Complete guide to creating views and Livewire components in the Nexus Identity system.

---

## Table of Contents

1. [View Architecture Overview](#view-architecture-overview)
2. [Livewire Component Structure](#livewire-component-structure)
3. [Creating New Views](#creating-new-views)
4. [Existing View Patterns](#existing-view-patterns)
5. [Form Handling Examples](#form-handling-examples)
6. [Component Lifecycle](#component-lifecycle)
7. [Styling & Layout](#styling--layout)
8. [Best Practices](#best-practices)

---

## View Architecture Overview

### Technology Stack

```
Frontend Layer
├── Livewire 4 (Real-time reactivity)
├── Tailwind CSS (Styling)
├── Alpine.js (DOM interactions)
└── Laravel Blade (Template engine)

File Structure
└── resources/
    ├── views/
    │   ├── components/
    │   ├── layouts/
    │   └── livewire/
    └── css/
        └── app.css (Tailwind output)
```

### Architecture Pattern

```
Route → Livewire Component → Blade View
                          ↓
                     Database Models
                          ↓
                    Audit Service
```

---

## Livewire Component Structure

### Basic Component Anatomy

Every Livewire component consists of two parts:

#### 1. PHP Class (Component Logic)

```php
<?php

namespace App\Livewire;

use Livewire\Component;

class MyComponent extends Component
{
    // Public properties (reactive)
    public $items = [];
    public $search = '';
    
    // Component initialization
    public function mount()
    {
        $this->items = Item::all();
    }
    
    // Event handlers
    public function delete($id)
    {
        Item::find($id)->delete();
        // Component re-renders automatically
    }
    
    // Render determines which view to display
    public function render()
    {
        return view('livewire.my-component');
    }
}
```

#### 2. Blade View (HTML Template)

```blade
<div>
    <h1>My Component</h1>
    
    <input wire:model="search" placeholder="Search...">
    
    <ul>
        @forelse($items as $item)
            <li wire:key="item-{{ $item->id }}">
                {{ $item->name }}
                <button wire:click="delete({{ $item->id }})">Delete</button>
            </li>
        @empty
            <li>No items found</li>
        @endforelse
    </ul>
</div>
```

---

## Creating New Views

### Step 1: Generate Component

Use Livewire's artisan command:

```bash
php artisan make:livewire MyNewComponent
```

This creates:
- `app/Livewire/MyNewComponent.php`
- `resources/views/livewire/my-new-component.blade.php`

### Step 2: Register Route

Add to `routes/web.php`:

```php
Route::get('/my-page', \App\Livewire\MyNewComponent::class)
    ->name('my.page')
    ->middleware('auth'); // Add auth if needed
```

### Step 3: Build the Component

#### Component Class Example

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class UserDetailView extends Component
{
    public $user;
    public $isEditing = false;
    public $editForm = [];
    
    public function mount($userId)
    {
        $this->user = User::find($userId);
        $this->editForm = $this->user->only('name', 'email');
    }
    
    public function toggleEdit()
    {
        $this->isEditing = !$this->isEditing;
    }
    
    public function save()
    {
        $this->user->update($this->editForm);
        $this->isEditing = false;
        
        session()->flash('success', 'User updated successfully');
    }
    
    public function render()
    {
        return view('livewire.user-detail-view');
    }
}
```

#### Blade View Example

```blade
<div class="max-w-2xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
            <button 
                wire:click="toggleEdit"
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
            >
                {{ $isEditing ? 'Cancel' : 'Edit' }}
            </button>
        </div>
        
        <!-- Content -->
        <div class="px-6 py-4">
            @if($isEditing)
                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Name</label>
                        <input 
                            type="text" 
                            wire:model="editForm.name"
                            class="w-full px-3 py-2 border rounded-md"
                        >
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Email</label>
                        <input 
                            type="email" 
                            wire:model="editForm.email"
                            class="w-full px-3 py-2 border rounded-md"
                        >
                    </div>
                    
                    <button 
                        type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
                    >
                        Save Changes
                    </button>
                </form>
            @else
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="font-semibold text-gray-500">Name</dt>
                        <dd>{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-gray-500">Email</dt>
                        <dd>{{ $user->email }}</dd>
                    </div>
                </dl>
            @endif
        </div>
        
        <!-- Footer -->
        @if(session()->has('success'))
            <div class="px-6 py-4 bg-green-50 border-t text-green-700">
                {{ session('success') }}
            </div>
        @endif
    </div>
</div>
```

---

## Existing View Patterns

### Pattern 1: List View with Filtering

**File**: `app/Livewire/Admin/UserList.php`

```php
class UserList extends Component
{
    public $search = '';
    public $sortBy = 'created_at';
    public $sortOrder = 'desc';
    
    public function updatedSearch()
    {
        // Live search as user types
    }
    
    public function sortBy($column)
    {
        // Toggle sort direction
    }
    
    public function getUsers()
    {
        return User::query()
            ->when($this->search, fn($q) => $q->where('email', 'like', "%{$this->search}%"))
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate(15);
    }
    
    public function render()
    {
        return view('livewire.admin.user-list', [
            'users' => $this->getUsers()
        ]);
    }
}
```

**Features**:
- Real-time search (uses `wire:model.live`)
- Sorting capabilities
- Pagination
- Reactive filtering

### Pattern 2: Form Modal Dialog

**File**: `app/Livewire/Admin/RoleManager.php`

```php
class RoleManager extends Component
{
    public $showModal = false;
    public $isEditing = false;
    public $role = [];
    public $permissions = [];
    public $selectedPermissions = [];
    
    public function openCreateModal()
    {
        $this->showModal = true;
        $this->isEditing = false;
        $this->reset('role');
    }
    
    public function openEditModal($id)
    {
        $this->showModal = true;
        $this->isEditing = true;
        $this->role = Role::find($id)->toArray();
        $this->selectedPermissions = $this->role->permissions->pluck('id')->toArray();
    }
    
    public function save()
    {
        // Create or update role, attach permissions
        // Re-render and close modal
    }
    
    public function closeModal()
    {
        $this->showModal = false;
    }
}
```

**Blade Pattern**:
```blade
<div>
    <!-- Main Content -->
    <div class="space-y-4">
        <button wire:click="openCreateModal" class="btn btn-primary">
            New Role
        </button>
        
        <!-- Table of roles -->
    </div>
    
    <!-- Modal Overlay -->
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-40"></div>
    @endif
    
    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
                <!-- Form Content -->
            </div>
        </div>
    @endif
</div>
```

### Pattern 3: Nested Component with Parent-Child Communication

**Parent**: `app/Livewire/Admin/UserAppAccessManager.php`

```php
class UserAppAccessManager extends Component
{
    public $user;
    public $userApps = [];
    
    public function mount($user)
    {
        $this->user = User::find($user);
        $this->loadUserApps();
    }
    
    public function loadUserApps()
    {
        $this->userApps = $this->user->appAccesses()->get();
    }
    
    // Listen for events from child component
    protected $listeners = ['appAccessUpdated' => 'loadUserApps'];
    
    public function addAppAccess($appId)
    {
        $this->user->appAccesses()->attach($appId);
        $this->loadUserApps();
    }
}
```

**Child Component**: Create `app/Livewire/AppAccessToggle.php`

```php
class AppAccessToggle extends Component
{
    public $app;
    public $user;
    public $hasAccess = false;
    
    public function mount($app, $user)
    {
        $this->app = $app;
        $this->user = User::find($user);
        $this->hasAccess = $this->user->appAccesses()->where('app_id', $app->id)->exists();
    }
    
    public function toggleAccess()
    {
        if ($this->hasAccess) {
            $this->user->appAccesses()->detach($this->app->id);
        } else {
            $this->user->appAccesses()->attach($this->app->id);
        }
        
        $this->hasAccess = !$this->hasAccess;
        
        // Emit event to parent
        $this->dispatch('appAccessUpdated');
    }
}
```

---

## Form Handling Examples

### Example 1: Create User Form

```php
<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Component
{
    public $form = [
        'name' => '',
        'email' => '',
        'password' => '',
        'password_confirmation' => '',
    ];
    
    public $errors = [];
    public $submitted = false;
    
    public function submit()
    {
        // Validate
        $validatedData = $this->validate([
            'form.name' => 'required|string|max:255',
            'form.email' => 'required|email|unique:users',
            'form.password' => 'required|string|min:8|confirmed',
        ]);
        
        // Create user
        $user = User::create([
            'name' => $this->form['name'],
            'email' => $this->form['email'],
            'password' => Hash::make($this->form['password']),
        ]);
        
        // Log action
        AuditService::log(
            auth()->user(),
            'created',
            'User',
            $user->id,
            [],
            $user->toArray()
        );
        
        // Reset and notify
        session()->flash('success', 'User created successfully');
        $this->reset('form');
    }
    
    public function render()
    {
        return view('livewire.admin.create-user');
    }
}
```

**Blade Template**:
```blade
<div class="max-w-md mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6">Create New User</h2>
    
    <form wire:submit="submit" class="space-y-4">
        <!-- Name Field -->
        <div>
            <label class="block text-sm font-medium mb-2">Name</label>
            <input 
                type="text" 
                wire:model="form.name"
                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500
                @error('form.name') border-red-500 @enderror"
            >
            @error('form.name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Email Field -->
        <div>
            <label class="block text-sm font-medium mb-2">Email</label>
            <input 
                type="email" 
                wire:model="form.email"
                class="w-full px-3 py-2 border rounded-md
                @error('form.email') border-red-500 @enderror"
            >
            @error('form.email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Password Field -->
        <div>
            <label class="block text-sm font-medium mb-2">Password</label>
            <input 
                type="password" 
                wire:model="form.password"
                class="w-full px-3 py-2 border rounded-md
                @error('form.password') border-red-500 @enderror"
            >
            @error('form.password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Confirm Password -->
        <div>
            <label class="block text-sm font-medium mb-2">Confirm Password</label>
            <input 
                type="password" 
                wire:model="form.password_confirmation"
                class="w-full px-3 py-2 border rounded-md"
            >
        </div>
        
        <!-- Submit Button -->
        <button 
            type="submit"
            wire:loading.attr="disabled"
            class="w-full px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 disabled:opacity-50"
        >
            <span wire:loading.remove>Create User</span>
            <span wire:loading>Creating...</span>
        </button>
    </form>
    
    @if(session()->has('success'))
        <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif
</div>
```

---

## Component Lifecycle

### Method Execution Order

```
1. __construct() - Component instantiation
2. boot() - Static initialization
3. mount() - Component mounting (receives parameters)
4. hydrate() - Property re-synchronization
5. updating() - Before property change
6. updated() - After property change
7. render() - View rendering
8. dehydrate() - Component state serialization
```

### Example with Lifecycle Hooks

```php
class ExampleComponent extends Component
{
    public $data = [];
    public $loading = false;
    
    public function mount($id)
    {
        // Called once when component is first loaded
        $this->loadData($id);
    }
    
    public function updatingSearch($value)
    {
        // Called before 'search' property updates
        $this->loading = true;
    }
    
    public function updatedSearch($value)
    {
        // Called after 'search' property updates (reactive)
        $this->data = $this->performSearch($value);
        $this->loading = false;
    }
    
    public function render()
    {
        return view('livewire.example-component');
    }
}
```

---

## Styling & Layout

### Tailwind CSS Integration

All views use Tailwind CSS for styling. Key patterns:

```blade
<!-- Card Component -->
<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Card Title</h3>
    <p class="text-gray-600">Card content goes here</p>
</div>

<!-- Button Variants -->
<button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Primary</button>
<button class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Secondary</button>
<button class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Danger</button>

<!-- Form Input -->
<input 
    type="text" 
    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
    placeholder="Enter text..."
>

<!-- Success Message -->
<div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded">
    Success message
</div>

<!-- Error Message -->
<div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded">
    Error message
</div>
```

### Layout Template

```blade
<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white">
            <!-- Navigation -->
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 overflow-auto">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
```

---

## Best Practices

### 1. Use Wire Modifiers Effectively

```blade
<!-- Debounce changes (wait 500ms after user stops typing) -->
<input wire:model.debounce-500ms="search">

<!-- Live updates (responds to every keystroke) -->
<input wire:model.live="search">

<!-- Lazy updates (only on blur/change) -->
<input wire:model.lazy="email">

<!-- Defer updates (only when form submitted) -->
<input wire:model.defer="name">
```

### 2. Optimize Performance

```php
// Bad: Queries database on every render
public function render()
{
    return view('view', [
        'users' => User::all() // ❌ Queried every time
    ]);
}

// Good: Load data once in mount
public function mount()
{
    $this->users = User::all(); // ✓ Loaded once
}

// Load data with relationships
public function mount()
{
    $this->user = User::with('roles', 'apps')->find($id); // ✓ Eager load
}
```

### 3. Use Keys for Lists

```blade
<!-- Good: Helps Livewire track items -->
@foreach($items as $item)
    <div wire:key="item-{{ $item->id }}">
        {{ $item->name }}
    </div>
@endforeach

<!-- Bad: No key, Livewire may lose state -->
@foreach($items as $item)
    <div>
        {{ $item->name }}
    </div>
@endforeach
```

### 4. Handle Errors Gracefully

```php
class MyComponent extends Component
{
    protected function rules()
    {
        return [
            'form.email' => 'required|email|unique:users',
            'form.name' => 'required|string|max:255',
        ];
    }
    
    public function submit()
    {
        $this->validate();
        
        // Process form
        $this->dispatch('success', 'Form submitted successfully');
    }
}
```

### 5. Keep Components Focused

```php
// Bad: Component doing too much
class AdminDashboard extends Component
{
    // ... handles users, roles, permissions, apps, all in one
}

// Good: Separate concerns
class UserManager extends Component { /* ... */ }
class RoleManager extends Component { /* ... */ }
class PermissionManager extends Component { /* ... */ }
class AppRegistry extends Component { /* ... */ }
```

### 6. Use Computed Properties (Livewire 4)

```php
use Livewire\Attributes\Computed;

class UserList extends Component
{
    public $search = '';
    
    #[Computed]
    public function filteredUsers()
    {
        return User::where('email', 'like', "%{$this->search}%")
            ->get();
    }
    
    public function render()
    {
        return view('livewire.user-list', [
            'users' => $this->filteredUsers // Computed automatically
        ]);
    }
}
```

### 7. Log All Critical Actions

```php
use App\Services\AuditService;

public function deleteUser($id)
{
    $user = User::find($id);
    $userData = $user->toArray();
    
    $user->delete();
    
    // Log the deletion
    AuditService::log(
        actor: auth()->user(),
        action: 'deleted',
        targetType: 'User',
        targetId: $id,
        before: $userData,
        after: [],
        metadata: ['reason' => 'Admin deletion']
    );
}
```

---

## Quick Reference: Common Component Methods

```php
// Navigation
redirect()->to('/path');
$this->redirect('/path');

// Flash messages
session()->flash('success', 'Operation completed');
$this->dispatch('alert', type: 'success', message: 'Saved!');

// Event dispatching
$this->dispatch('eventName', data: $value);

// Resetting properties
$this->reset('property1', 'property2');
$this->resetExcept('important');
$this->resetValidation();

// Validation
$this->validate();
$this->validate(['email' => 'required|email']);
$this->validateOnly('email');

// Getting resources by ID from route
$this->user = User::findOrFail($id);
```

---

## File Organization

### Recommended Directory Structure

```
app/
├── Livewire/
│   ├── Dashboard.php
│   ├── Admin/
│   │   ├── UserList.php
│   │   ├── RoleManager.php
│   │   ├── AuditLogViewer.php
│   │   └── AppManager.php
│   ├── Profile/
│   │   ├── ApiTokens.php
│   │   ├── Security.php
│   │   └── Devices.php
│   └── Auth/
│       └── LoginForm.php

resources/
├── views/
│   ├── layouts/
│   │   ├── app.blade.php
│   │   └── guest.blade.php
│   ├── components/
│   │   ├── sidebar.blade.php
│   │   ├── navbar.blade.php
│   │   └── button.blade.php
│   └── livewire/
│       ├── dashboard.blade.php
│       ├── admin/
│       │   ├── user-list.blade.php
│       │   ├── role-manager.blade.php
│       │   └── app-manager.blade.php
│       └── profile/
│           ├── api-tokens.blade.php
│           └── security.blade.php
```

---

## Testing Your New View

### Manual Testing Flow

1. **Create Component**: `php artisan make:livewire MyComponent`
2. **Register Route**: Add to `routes/web.php`
3. **Build Blade View**: Create template in `resources/views/livewire/`
4. **Test in Browser**: Navigate to route and test interactions
5. **Check Audit Logs**: Verify actions logged in `/admin/audit-logs`
6. **Test Responsiveness**: Check mobile layout

---

## Troubleshooting

| Issue | Solution |
|-------|----------|
| Component not rendering | Check route registration and view file exists |
| Reactive updates not working | Verify `wire:model` directive used correctly |
| Component state lost | Ensure public properties defined in class |
| Performance degradation | Load data in `mount()`, use `#[Computed]`, eager load relationships |
| Modal not showing | Check `@if` condition and z-index layering |
| Validation errors not displaying | Use `@error` directive in template |
| CSRF token mismatch | Verify `@csrf` in forms or Livewire auto-handling |

---

## Next Steps

1. Review existing components in `app/Livewire/` for patterns
2. Start with simple components (list view) before complex ones (forms with modals)
3. Use `php artisan make:livewire` to scaffold components
4. Reference [Livewire Documentation](https://livewire.laravel.com) for advanced features
5. Check audit logs when testing to ensure actions are captured

