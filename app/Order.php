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

	public  function orders_status()
	{

		return $this->hasOne(OrdersStatus::class,'orders_status_id');
	}
	
	public  function orders_products()
	{

		return $this->hasMany(OrdersProduct::class,'orders_id');
	}
	public function orders_products_attributes(){

		return $this->hasOne(OrdersProductsAttribute::class,'orders_id','orders_id');
	}  
}
