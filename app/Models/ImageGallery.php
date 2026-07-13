<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageGallery extends Model
{
    protected $fillable = ['language_id','image_album_id','gallery','staff_id','title'];
    protected $table    = 'image_galleries';
    public $timestamps  = false;

    public function language(){
        return $this->belongsTo('App\Models\Language')->withDefault(function ($data) {
            foreach($data->getFillable() as $dt){
                $data[$dt] = __('Deleted');
            }
        });
    }
	
	public function user(){
        return $this->belongsTo('App\Models\User','staff_id');
    }

    public function album(){
        return $this->belongsTo('App\Models\ImageAlbum','image_album_id')->withDefault(function ($data) {
            foreach($data->getFillable() as $dt){
                $data[$dt] = __('Deleted');
            }
        });
    }
   
}
