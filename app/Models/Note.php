<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;


class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'folder_id',
        'name',
        'description',
        'file_path',
        'file_type',
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
        return $this->belongsTo(NoteFolder::class, 'folder_id');
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

    protected function filePath(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Storage::url($value) : null,
        );
    }
}
