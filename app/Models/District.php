<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = [
		'id',
		'division_id',
        'name',
        'bn_name',
        'url',
    
    ];
    protected $table    = 'districts';

 
}
