<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
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

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
