<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Order extends Model
{
 	protected $guard = "customers";
  
	protected $table = 'orders';

	protected $guarded = ['orders_id'];

	//use user id of admin
	protected $primaryKey = 'orders_id';   
}
