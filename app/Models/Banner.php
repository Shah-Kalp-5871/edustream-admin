<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use SoftDeletes; // Assuming HasFactory is not needed or will be added separately if it was intended. The instruction specifically asks for SoftDeletes.
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
