<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'order_number',
        'total_amount',
        'payment_status',
        'payment_method',
        'payment_id',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
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
