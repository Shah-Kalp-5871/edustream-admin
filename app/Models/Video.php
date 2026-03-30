<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;


class Video extends Model
{
    use HasFactory, SoftDeletes;

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
        'hls_path',
        'processing_status',
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

    protected function videoUrl(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (!$value) return null;
                if (filter_var($value, FILTER_VALIDATE_URL)) {
                    return $value;
                }
                return Storage::disk('public')->url($value);
            },
        );
    }
}
