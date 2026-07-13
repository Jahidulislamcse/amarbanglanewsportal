<?php


namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ReceivePayment extends Model
{
  
    protected $fillable = [
        'payment_type', 'amount','pdate','user_id','admin_id','txn_id','send_number','status'
    ];

    protected $table    = 'payment_reporters';

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
