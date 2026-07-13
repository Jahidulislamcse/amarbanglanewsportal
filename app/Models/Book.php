<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'pdf_file', 'price', 'status', 'cover'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'book_purchases')
                    ->withTimestamps();
    }
}