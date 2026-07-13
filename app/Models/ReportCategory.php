<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportCategory extends Model
{
    protected $fillable = ['title_bn','title_en','status'];
    protected $table    = 'reportcategories';
    public $timestamps  = false;

}
