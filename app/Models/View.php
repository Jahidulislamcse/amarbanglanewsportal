<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    protected $fillable = ['post_id','user_id','ip_address','view_count','created_at'];
    protected $table = 'views';
    public $timestamps = false;

    public function post()
    {
        return $this->belongsTo('App\Models\Post')->withDefault(function ($data) {
            foreach($data->getFillable() as $dt){
                $data[$dt] = __('Deleted');
            }
        });
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withDefault(function ($data) {
            $data->name = __('Deleted User');
        });
    }
}
