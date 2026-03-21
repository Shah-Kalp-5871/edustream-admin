<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'item_type',
        'item_id',
        'bundle_subjects',
        'price',
    ];

    protected $casts = [
        'bundle_subjects' => 'array',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function item()
    {
        return $this->morphTo();
    }
}
