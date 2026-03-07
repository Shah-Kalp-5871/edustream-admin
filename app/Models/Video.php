<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'folder_id',
        'name',
        'file_path',
        'description',
        'video_url',
        'video_source',
        'duration',
        'thumbnail_url',
        'is_free',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'is_free' => 'boolean',
    ];

    // Relationships
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function folder()
    {
        return $this->belongsTo(VideoFolder::class, 'folder_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }
}
