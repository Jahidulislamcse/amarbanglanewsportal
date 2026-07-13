<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Thana extends Model
{
    protected $fillable = [
		'id',
		'district_id',
        'name',
        'bn_name',
        'url',
    
    ];
    protected $table    = 'upazilas';

 
}
