<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'name',
        'slug',
        'description',
        'icon_url',
        'color_code',
        'thumbnail_url',
        'price',
        'is_free',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'is_free' => 'boolean',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function noteFolders()
    {
        return $this->hasMany(NoteFolder::class)->whereNull('parent_id')->orderBy('sort_order');
    }

    public function notes()
    {
        return $this->hasMany(Note::class)->orderBy('sort_order');
    }

    public function videoFolders()
    {
        return $this->hasMany(VideoFolder::class)->whereNull('parent_id')->orderBy('sort_order');
    }

    public function videos()
    {
        return $this->hasMany(Video::class)->orderBy('sort_order');
    }

    public function qaPaperFolders()
    {
        return $this->hasMany(QaPaperFolder::class)->whereNull('parent_id')->orderBy('sort_order');
    }

    public function qaPapers()
    {
        return $this->hasMany(QaPaper::class)->orderBy('sort_order');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class)->orderBy('sort_order');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function cartItems()
    {
        return $this->morphMany(CartItem::class, 'item');
    }

    public function orderItems()
    {
        return $this->morphMany(OrderItem::class, 'item');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }



    protected function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Storage::url($value) : null,
        );
    }
}
