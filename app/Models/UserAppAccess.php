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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function app()
    {
        return $this->belongsTo(App::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function validateRoleBelongsToApp()
    {
        if ($this->role && $this->role->app_id && $this->role->app_id != $this->app_id) {
            throw new \InvalidArgumentException("The selected role does not belong to this application.");
        }

        // Ensure status is valid
        if (!in_array($this->status, ['active', 'suspended', 'inactive'])) {
            // Fallback or error, for now we let it pass if it matches DB default, 
            // but stricter validation could be added here.
        }
    }
}
