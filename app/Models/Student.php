<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes; // Added this line

use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class Student extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes; // Added SoftDeletes here

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'course_id',
        'avatar_url',
        'plan',
        'status',
        'email_verified_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationships
    public function refreshTokens()
    {
        return $this->hasMany(RefreshToken::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePremium($query)
    {
        return $query->where('plan', 'premium');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $appends = ['is_premium'];

    public function getIsPremiumAttribute()
    {
        // 1. Check if manually set to premium plan
        if ($this->plan === 'premium') {
            return true;
        }

        // 2. Check for any active enrollments that were part of a paid order
        // This covers both specific course/subject purchases and active subscriptions
        return $this->enrollments()
            ->active()
            ->whereHas('order', function ($query) {
                $query->where('payment_status', 'completed')
                    ->where('total_amount', '>', 0);
            })
            ->exists();
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Storage::disk('public')->url($value) : null,
        );
    }
}
