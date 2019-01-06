<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentDescription extends Model
{
    protected $table = 'payment_description';

	protected $guarded = ['id'];

	//use user id of admin
	protected $primaryKey = 'id'; 
}
