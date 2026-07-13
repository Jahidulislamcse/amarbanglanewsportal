<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'admins';
    protected $fillable = ['name', 'email', 'password', 'phone', 'photo', 'role_id', 'salary', 'account_details', 'status'];

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'role_id');
    }

    public function salaries()
    {
        return $this->hasMany(Salary::class, 'employee_id');
    }

    public function advanceSalaries()
    {
        return $this->hasMany(AdvanceSalary::class, 'employee_id');
    }
}
