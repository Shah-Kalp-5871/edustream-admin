<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppVersion extends Model
{
    protected $fillable = [
        'version_name',
        'version_code',
        'apk_url',
        'is_force_update'
    ];
    
    protected $casts = [
        'is_force_update' => 'boolean',
    ];
}
