<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;

    protected $fillable = [
        'rep_monthly_fee',
        'reader_view_rate',
        'free_reader_rate',
        'executive_reader_rate',
        'vip_reader_rate',
        'referral_view_commission',
        'reporter_view_rate',
        'referral_commission',
        'common_reffer_commission',
        
        'executive_package_price',
        'vip_package_price',
        'withdraw_min',

        'reporter_1st_prize',
        'reporter_2nd_prize',
        'reporter_3rd_prize',
        'quiz_1st_prize',
        'quiz_2nd_prize',
        'quiz_3rd_prize'
    ];

    protected $casts = [
        'rep_monthly_fee' => 'float',
        'reader_view_rate' => 'float',
        'reporter_view_rate' => 'float',
        'referral_commission' => 'float',
        'referral_view_commission' => 'float',
        'quiz_1st_prize' => 'float',
        'quiz_2nd_prize' => 'float',
        'quiz_3rd_prize' => 'float'
    ];
}
