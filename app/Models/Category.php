<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;


class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon_url',
        'status',
        'sort_order',
    ];

    // Relationships
    public function courses()
    {
        return $this->hasMany(Course::class)->orderBy('sort_order');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    protected function iconUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Storage::url($value) : null,
        );
    }
}
