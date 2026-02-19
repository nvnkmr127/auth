<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'phone',
        'otp_enabled',
        'last_login_at',
        'last_login_ip',
        'last_login_device',
        'last_login_location',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'otp_enabled' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }
    public function appAccesses()
    {
        return $this->hasMany(UserAppAccess::class);
    }

    public function apiTokens()
    {
        return $this->hasMany(ApiToken::class);
    }

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->using(UserRole::class)
            ->withPivot('app_id')
            ->withTimestamps();
    }

    /**
     * Check if user is a system admin.
     */
    public function isAdmin(): bool
    {
        return $this->roles()
            ->where('is_global', true)
            ->where('key', 'super_admin')
            ->exists();
    }

    /**
     * Check if user has a specific role (optionally app-scoped).
     */
    public function hasRole(string $roleKey, ?int $appId = null): bool
    {
        $query = $this->roles()->where('key', $roleKey);

        if ($appId) {
            $query->where('app_id', $appId);
        } else {
            $query->where('is_global', true);
        }

        return $query->exists();
    }

    /**
     * Check if user has a specific permission (globally or for an app).
     */
    public function hasPermission(string $permissionKey, ?int $appId = null): bool
    {
        // Global administrative override
        if ($this->isAdmin()) {
            return true;
        }

        return $this->roles()
            ->where(function ($query) use ($appId) {
                if ($appId) {
                    $query->where('app_id', $appId);
                } else {
                    $query->where('is_global', true);
                }
            })
            ->whereHas('permissions', function ($query) use ($permissionKey) {
                $query->where('key', $permissionKey);
            })
            ->exists();
    }

    /**
     * Synchronize the user's role based on an SSO role key.
     */
    public function syncSsoRole(?string $roleKey): void
    {
        if (!$roleKey) {
            return;
        }

        $role = Role::where('key', $roleKey)->first();

        if ($role) {
            $this->roles()->sync([$role->id]);
        }
    }
}
