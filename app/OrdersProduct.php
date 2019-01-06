<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class OrdersProduct extends Model
{
   
	protected $table = 'orders_products';

	protected $guarded = ['orders_products_id'];

	//use user id of admin
	protected $primaryKey = 'orders_products_id'; 
}
