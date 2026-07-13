<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageAlbum extends Model
{
    protected $fillable = ['language_id','photo','album_name','album_description','image_category_id','slug','user_id'];
    protected $table    = 'image_albums';
    public $timestamps  = false;

    public function language(){
        return $this->belongsTo('App\Models\Language')->withDefault(function ($data) {
            foreach($data->getFillable() as $dt){
                $data[$dt] = __('Deleted');
            }
        });
    }
    public function categories(){
        return $this->hasMany('App\Models\ImageCategory','image_album_id');
    }
    public function galleries(){
        return $this->hasMany('App\Models\ImageGallery','image_album_id');
    }
	
	public function user(){
        return $this->hasMany('App\Models\User','user_id');
    }
	
    public function category(){
        return $this->belongsTo('App\Models\ImageCategory','image_category_id')->withDefault(function ($data) {
            foreach($data->getFillable() as $dt){
                $data[$dt] = __('Deleted');
            }
        });
    }
	
}
