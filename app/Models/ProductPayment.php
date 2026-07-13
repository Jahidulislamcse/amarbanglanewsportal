<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPayment extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'transaction_id',
        'product_name',
        'quantity',
        'unit_price',
        'amount',
        'phone_number',
        'address',
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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
