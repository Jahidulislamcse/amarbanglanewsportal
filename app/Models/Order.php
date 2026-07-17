<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'transaction_id',
        'total_amount',
        'phone_number',
        'address',
        'status'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->hasOne(ProductPayment::class);
    }

    protected static function booted()
    {
        static::created(function ($order) {
            $user = $order->user;
            if ($user && $user->referrer && $order->total_amount > 0) {
                $referrer = $user->referrer;

                // Determine delivery charge
                $deliveryCharge = 0;
                if ($order->address) {
                    if (strpos($order->address, '[Zone: Inside Dhaka]') !== false) {
                        $deliveryCharge = 80;
                    } elseif (strpos($order->address, '[Zone: Outside Dhaka]') !== false) {
                        $deliveryCharge = 120;
                    }
                }

                // Commission is calculated only on the product amount (excluding courier fee)
                $productAmount = max(0, $order->total_amount - $deliveryCharge);
                $commissionAmount = $productAmount * 0.10;

                // Create ProductCommission record
                \App\Models\ProductCommission::create([
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                    'referrer_id' => $referrer->id,
                    'order_amount' => $productAmount,
                    'commission_rate' => 10.00,
                    'commission_amount' => $commissionAmount,
                ]);

                // Increment referrer balance and referral earning
                $referrer->increment('balance', $commissionAmount);
                $referrer->increment('referral_earning', $commissionAmount);
            }
        });
    }
}
