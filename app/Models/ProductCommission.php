<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'referrer_id',
        'order_amount',
        'commission_rate',
        'commission_amount'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }
}
