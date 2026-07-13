<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyFeePayment extends Model
{
    protected $fillable = [
        'user_id',
        'transaction_id',
        'amount',
        'status',
        'gateway_response',
        'eps_transaction_id',
        'payment_type',
        'paid_at',
    ];

    protected $casts = [
        'gateway_response' => 'array',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}