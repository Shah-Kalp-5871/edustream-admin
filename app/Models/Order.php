<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'order_number',
        'total_amount',
        'payment_status',
        'payment_method',
        'payment_id',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
