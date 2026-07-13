<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $fillable = [
        'employee_id',
        'year',
        'month',
        'basic_salary',
        'advance_paid',
        'salary_paid',
        'status',
        'payment_date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
