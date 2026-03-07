<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QaPaperFolder extends Model
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
        return $this->belongsTo(QaPaperFolder::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(QaPaperFolder::class, 'parent_id')->orderBy('sort_order');
    }

    public function qaPapers()
    {
        return $this->hasMany(QaPaper::class, 'folder_id')->orderBy('sort_order');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
