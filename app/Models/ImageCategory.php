<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageCategory extends Model
{
    protected $fillable = ['language_id','name','slug'];
    protected $table    = 'image_categories';
    public $timestamps  = false;

    public function language(){
        return $this->belongsTo('App\Models\Language');
    }
   
}
