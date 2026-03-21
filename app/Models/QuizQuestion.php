<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizQuestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'type',
        'marks',
        'image_url',
        'sort_order',
    ];

    // Relationships
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function options()
    {
        return $this->hasMany(QuizOption::class, 'question_id')->orderBy('sort_order');
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class, 'question_id');
    }

    // Scopes
    public function scopeMcq($query)
    {
        return $query->where('type', 'mcq');
    }
}
