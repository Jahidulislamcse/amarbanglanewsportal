<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'language_id',
        'title',
        'slug',
        'meta_tag',
        'show_right_column',
        'is_feature',
        'is_slider',
        'is_trending',
        'is_videoGallery',
        'tags',
        'description',
        'image_big',
        'rss_image',
        'image_small',
        'video',
        'audio',
        'category_id',
        'subcategories_id',
        'death_count',
        'admin_id',
        'user_id',
        'status',
        'schedule_post',
        'schedule_post_date',
        'is_pending',
        'post_type',
        'slider_left',
        'slider_right',
        'rss_link',
        'embed_video',
		'division_id',
		'district_id',
		'thana_id',
		'view_count',
		'unique_count',
		'current_count',
		'approve_by',
		'cover_image_id',
		'image_note',
		'reject_reason',
		'approved_at',
		'rejected_by',

    ];
    protected $table    = 'posts';

    protected $dates = [
        'created_at',
        'updated_at',
        'schedule_post_date',
        'approved_at',
    ];

    public function category(){
        return $this->belongsTo('App\Models\Category','category_id')->withDefault(function ($data) {
            foreach($data->getFillable() as $dt){
                $data[$dt] = __('Deleted');
            }
        });
    }
    
    public function cover_image()
    {
        return $this->belongsTo(\App\Models\NewsCover::class, 'cover_image_id');
    }

    
    public function subcategory(){
        return $this->belongsTo('App\Models\Category','subcategories_id');
    }

    public function language(){
        return $this->belongsTo('App\Models\Language')->withDefault(function ($data) {
            foreach($data->getFillable() as $dt){
                $data[$dt] = __('Deleted');
            }
        });
    }

    public function admin(){
        return $this->belongsTo('App\Models\Admin')->withDefault(function ($data) {
            foreach($data->getFillable() as $dt){
                $data[$dt] = __('');
            }
        });
    }

    public function user(){
        return $this->belongsTo('App\Models\User')->withDefault(function ($data) {
            foreach($data->getFillable() as $dt){
                $data[$dt] = __('');
            }
        });
    }

    public function galleries(){
        return $this->hasMany('App\Models\Gallery','post_id');
    }
    public function createdAt(){
        $date = $this->created_at;
        return $date->toFormattedDateString();
    }
    public function views(){
        return $this->hasMany('App\Models\View','post_id');
    }

    public function tquizs(){
        return $this->hasMany('App\Models\TriviaQuestion','post_id');
    }

    public function tresults(){
        return $this->hasMany('App\Models\TriviaResult','post_id');
    }

    public function pquizs(){
        return $this->hasMany('App\Models\PersonalityQuestion','post_id');
    }

    public function presults(){
        return $this->hasMany('App\Models\PersonalityResult','post_id');
    }

    public function sorts(){
        return $this->hasMany('App\Models\ShortList','post_id');
    }
    public function approvedBy()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'approve_by');
    }
    public function rejectedBy()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'rejected_by');
    }
    public function quiz()
    {
        return $this->hasOne(PostQuiz::class, 'post_id');
    }
    
    public function quizAnswers()
    {
        return $this->hasMany(PostQuizAnswer::class, 'post_id');
    }
}
