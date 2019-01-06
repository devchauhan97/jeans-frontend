<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Coupon extends Model
{
   
  
	protected $table = 'coupons';

	protected $guarded = ['coupans_id'];

	//use user id of admin
	protected $primaryKey = 'coupans_id'; 
}
