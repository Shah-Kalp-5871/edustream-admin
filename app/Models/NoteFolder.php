<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NoteFolder extends Model
{
    use HasFactory, SoftDeletes;

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
        return $this->belongsTo(NoteFolder::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(NoteFolder::class, 'parent_id')->orderBy('sort_order');
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'folder_id')->orderBy('sort_order');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
