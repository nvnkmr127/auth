<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsedSsoToken extends Model
{
    protected $fillable = ['jti', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
