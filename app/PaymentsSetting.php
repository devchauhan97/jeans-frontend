<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentsSetting extends Model
{
    protected $table = 'payments_setting';

	protected $guarded = ['payments_id'];

	//use user id of admin
	protected $primaryKey = 'payments_id'; 
}
