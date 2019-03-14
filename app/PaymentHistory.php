<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
     protected $table = 'payment_histories';
	
	protected $guarded = ['payment_histories_id'];

	protected $primaryKey = 'payment_histories_id'; 
}
