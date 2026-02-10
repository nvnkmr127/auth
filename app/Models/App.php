<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    protected $fillable = ['name', 'slug', 'domain', 'status'];

    public function userAccesses()
    {
        return $this->hasMany(UserAppAccess::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }
}
