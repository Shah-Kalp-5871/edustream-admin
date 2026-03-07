<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoFolder extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'parent_id',
        'name',
        'status',
        'sort_order',
    ];

    // Relationships
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function parent()
    {
        return $this->belongsTo(VideoFolder::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(VideoFolder::class, 'parent_id')->orderBy('sort_order');
    }

    public function videos()
    {
        return $this->hasMany(Video::class, 'folder_id')->orderBy('sort_order');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
