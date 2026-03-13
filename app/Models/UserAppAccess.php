<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAppAccess extends Model
{
    protected $table = 'user_app_accesses';

    protected $fillable = ['user_id', 'app_id', 'role_id', 'status'];

    protected $casts = [
        'status' => 'string',
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->validateRoleBelongsToApp();
        });
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function app(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(App::class);
    }

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function validateRoleBelongsToApp(): void
    {
        if ($this->role && $this->role->app_id && $this->role->app_id != $this->app_id) {
            throw new \InvalidArgumentException("The selected role does not belong to this application.");
        }

        // Validate status is one of the allowed values
        $allowedStatuses = ['active', 'suspended', 'inactive'];
        if ($this->status && !in_array($this->status, $allowedStatuses)) {
            throw new \InvalidArgumentException("Invalid status. Allowed values are: " . implode(', ', $allowedStatuses));
        }
    }
}
