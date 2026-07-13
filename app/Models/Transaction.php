<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'type',
        'title',
        'bearer',
        'amount',
        'transaction_date',
        'note',
        'category_id'
    ];

    public function trcategory()
    {
        return $this->belongsTo(TransactionCategory::class, 'category_id');
    }
}
