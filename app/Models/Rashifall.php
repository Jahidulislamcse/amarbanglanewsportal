<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rashifall extends Model
{
    protected $fillable = ['user_id','language_id','description_1','description_2','description_3','description_4','description_5','description_6','description_7','description_8','description_9','description_10','description_11','description_12','date','type'];
    protected $table    = 'rashifalls';

    public function user(){
        return $this->belongsTo('App\Models\User')->withDefault(function ($data) {
            foreach($data->getFillable() as $dt){
                $data[$dt] = __('Deleted');
            }
        });
    }
	
	public function language(){
        return $this->belongsTo('App\Models\Language')->withDefault(function ($data) {
            foreach($data->getFillable() as $dt){
                $data[$dt] = __('Deleted');
            }
        });
    }
}
