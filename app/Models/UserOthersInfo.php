<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOthersInfo extends Model
{
    protected $table = 'user_others_infos';

    protected $fillable = [
        'user_id',
        'password',
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}