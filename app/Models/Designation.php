<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $table = 'roles';
    public $timestamps = false;
    protected $fillable = ['name', 'name_bn', 'section', 'code'];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'role_id');
    }
}
