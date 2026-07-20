<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'photo',
        'nid',
        'nid_back',
        'father_name',
        'mother_name',
        'address',
        'phone',
        'nid_no',
        'email',
        'password',
        'plain_password',
        'verification_link',
        'affilate_code',
        'referred_by',
        'is_provider',
        'details',
        'experience',
        'has_experience',
        'experience_organization',
        'experience_designation',
        'division_id',
        'district_id',
        'thana_id',
        'union_id',
        'permanent_division_id',
        'permanent_district_id',
        'permanent_thana_id',
        'permanent_union_id',
        'report_type',
        'approve_report_type',
        'reporter_area',
        'is_approve',
        'is_staff',
        'verified',
		'blood',
		'eduaction',
		'education_year',
		'dob',
		'is_reader',
		'views',
		'view_income',
		'referral_earning',
		'approved_date',
        'next_payment_date',
        'payment_status',
        'balance',
        'reader_type',         
        'team_gen_1',           
        'team_gen_2',           
        'team_gen_3',          
        'team_gen_4',           
        'team_gen_5',           
        'promotion_eligible',  
        'daily_quiz_money',
        'is_ban',
        'package1_purchased',
        'package_bypass_until',
     ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts(){
        return $this->hasMany('App\Models\Post','user_id');
    }
    
    public function division()
    {
        return $this->belongsTo(\App\Models\Division::class, 'division_id');
    }
    
    public function district()
    {
        return $this->belongsTo(\App\Models\District::class, 'district_id');
    }
    
    public function upazila()
    {
        return $this->belongsTo(\App\Models\Thana::class, 'thana_id');
    }
    
    public function union()
    {
        return $this->belongsTo(\App\Models\Unions::class, 'union_id');
    }
    
    public function permanentDivision()
    {
        return $this->belongsTo(\App\Models\Division::class, 'permanent_division_id');
    }
    
    public function permanentDistrict()
    {
        return $this->belongsTo(\App\Models\District::class, 'permanent_district_id');
    }
    
    public function permanentUpazila()
    {
        return $this->belongsTo(\App\Models\Thana::class, 'permanent_thana_id');
    }
    
    public function permanentUnion()
    {
        return $this->belongsTo(\App\Models\Unions::class, 'permanent_union_id');
    }
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }
    
    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }
    public function coursePurchases()
    {
        return $this->hasMany(CoursePurchase::class);
    }
    public function monthlyFeePayments()
    {
        return $this->hasMany(
            MonthlyFeePayment::class,
            'user_id'
        );
    }

    public function prizeMoneys()
    {
        return $this->hasMany(UserPrizeMoney::class, 'user_id');
    }
}
