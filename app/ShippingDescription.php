<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingDescription extends Model
{
   protected $table = 'shipping_description';

	protected $guarded = ['id'];

	//use user id of admin
	protected $primaryKey = 'id';  
}
