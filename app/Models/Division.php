<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $fillable = [
		'id',
        'name',
        'bn_name',
        'url',
    
    ];
    protected $table    = 'divisions';
    
    public function admins()
    {
        return $this->belongsToMany(
            Admin::class,
            'admin_division',
            'division_id',
            'admin_id'
        );
    }

 
}
