<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvanceSalary extends Model
{
    protected $fillable = ['employee_id', 'year', 'month', 'amount', 'payment_date', 'notes'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
