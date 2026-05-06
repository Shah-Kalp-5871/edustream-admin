<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Download extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'content_id',
        'content_type',
        'title',
        'file_name',
        'file_path',
        'file_url',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
