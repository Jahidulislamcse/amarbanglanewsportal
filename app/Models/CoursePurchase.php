<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoursePurchase extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'transaction_id',
        'phone_number',
        'operator',
        'amount',
        'status',
        'gateway_response',
        'payment_type',
        'paid_at',
    ];

    protected $casts = [
        'gateway_response' => 'array',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
