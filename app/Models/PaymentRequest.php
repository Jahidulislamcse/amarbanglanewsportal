<?php


namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
  
    protected $fillable = [
        'payment_type', 'request_amount', 'approve_amount','request_date','user_id','admin_id','verify_date','account_details','check_id','status', 'user_amount'
    ];

    protected $table    = 'payment_requests';

    protected $dates = [
        'created_at',
        'updated_at'
    ];
   
	public function verifier()
	{
		return $this->belongsTo(\App\Models\Admin::class, 'admin_id')->withDefault(function ($admin) {
			$admin->name = __('Deleted'); // only override the name
		});
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class, 'user_id');
	}


 

}
