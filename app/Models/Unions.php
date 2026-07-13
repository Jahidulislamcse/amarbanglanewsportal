<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Unions extends Model
{
    protected $fillable = [
		'id',
		'upazilla_id',
        'name',
        'bn_name',
        'url',
    
    ];
    protected $table    = 'unions';

 
}
