<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['title','price','status'];

    public function modules()
    {
        return $this->hasMany(Module::class);
    }
    public function purchases()
    {
        return $this->hasMany(CoursePurchase::class);
    }
}