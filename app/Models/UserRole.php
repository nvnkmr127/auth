<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserRole extends Pivot
{
    protected $table = 'user_roles';

    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'role_id',
        'app_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function app()
    {
        return $this->belongsTo(App::class);
    }
}
