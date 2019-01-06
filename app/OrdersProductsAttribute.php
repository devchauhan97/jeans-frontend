<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class OrdersProductsAttribute extends Model
{
   protected $guard = "customers";
  
	protected $table = 'orders_products_attributes';

	protected $guarded = ['orders_products_attributes_id'];

	//use user id of admin
	protected $primaryKey = 'orders_products_attributes_id'; 
}
