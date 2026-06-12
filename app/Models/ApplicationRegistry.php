<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationRegistry extends Model
{
    protected $table = 'application_registry';

    protected $fillable = [
        'app_name',
        'app_slug',
        'app_icon',
        'app_type',
        'app_version',
        'mobile_enabled',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'mobile_enabled' => 'boolean',
        'sort_order' => 'integer',
    ];
}
