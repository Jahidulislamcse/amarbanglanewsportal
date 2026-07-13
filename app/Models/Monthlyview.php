<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monthlyview extends Model
{
    protected $fillable   = ['user_id','post_id','total_view','unique_view','current_view','mdate'];
    protected $table      = 'monthlyviews';
    public    $timestamps = false;

    public function post(){
        return $this->belongsTo('App\Models\Post')->withDefault(function ($data) {
            foreach($data->getFillable() as $dt){
                $data[$dt] = __('Deleted');
            }
        });
    }
	
	public function user(){
        return $this->belongsTo('App\Models\User')->withDefault(function ($data) {
            foreach($data->getFillable() as $dt){
                $data[$dt] = __('Deleted');
            }
        });
    }
}
