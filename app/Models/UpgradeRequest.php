<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpgradeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package',
        'amount',
        'phone_number',
        'operator',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}