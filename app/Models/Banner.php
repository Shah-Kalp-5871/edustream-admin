<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'icon',
        'color_start',
        'color_end',
        'image_path',
        'redirect_url',
        'status',
        'sort_order',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
