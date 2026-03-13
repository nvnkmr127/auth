<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'ip_address',
        'user_agent',
        'payload',
        'last_activity'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include sessions for authenticated users.
     */
    public function scopeAuthenticated($query)
    {
        return $query->whereNotNull('user_id');
    }

    /**
     * Get the last activity as a Carbon instance.
     */
    public function getLastActivityAttribute($value)
    {
        return \Carbon\Carbon::createFromTimestamp($value);
    }
}
