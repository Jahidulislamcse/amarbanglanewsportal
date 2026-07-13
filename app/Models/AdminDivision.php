<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminDivision extends Model
{
    use HasFactory;

    protected $table = 'admin_division';

    public $timestamps = false;

    protected $fillable = [
        'admin_id',
        'division_id',
    ];
}
